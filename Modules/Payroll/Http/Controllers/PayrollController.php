<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\User;
use Modules\Absensi\Entities\Jadwal;
use Modules\Payroll\Entities\Payroll;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use GuzzleHttp\Client;
use App\Configs;
use PDF;

class PayrollController extends Controller
{
  public function __construct()
  {
    $this->configs = Configs::getAll();
  }

  public function index()
  {
    $data = [
      'title' => 'Hitung Gaji Pegawai',
      'jadwal' => [],
      'users' => []
    ];
    return view('payroll::logs.index',$data);
  }

  public function showLogs(Request $r)
  {
    $users = User::where('role','!=','admin')
    ->when($r->status,function($q,$role){
      $q->whereHas('pegawai',function($q) use($role){
        $q->where('status_kepegawaian',$role);
      });
    })
    ->when($r->user,function($q,$role){
      $q->whereIn('id',$role);
    })
    ->whereHas('payroll')
    ->orderBy('name','asc')
    ->get();

    if (!count($users)) {
      return redirect()->route('payroll.log.index')->withErrors(['User tidak tersedia']);
    }

    $dates = Carbon::parse($r->start_date)->toPeriod($r->end_date);

    $logs = $this->getLogs($users,$dates,$r);

    $jadwal = [];

    if ($r->jadwal) {
      $jadwal = Jadwal::whereIn('id',$r->jadwal)->get();
    }

    $data = [
      'title' => 'Hitung Gaji Pegawai',
      'users' => $users,
      'jadwal' => $jadwal,
      'config' => $this->configs,
      'data' => $logs,
    ];

    if ($r->download_pdf) {
      if (!count($logs)) {
        return redirect()->route('payroll.log.index')->withErrors(['gaji pegawai tidak tersedia!']);
      }
      if (request()->user && count($users)==1) {
        $data['title'] = 'Gaji Pegawai - '.$users[0]->name.' ('.Carbon::now()->locale('id')->translatedFormat('j F Y').')';
      }else{
        $data['title'] = 'Gaji Pegawai ('.Carbon::now()->locale('id')->translatedFormat('j F Y').')';
      }

      $params = [
        'page-width'=>'21.5cm',
        'page-height'=>'33cm',
      ];
      if (!request()->user||count($users)>1) {
        $params['orientation'] = 'landscape';
      }

      $filename = $data['title'].'.pdf';

      $pdf = PDF::loadView('payroll::logs.print',$data)
      ->setOptions($params);
      return $pdf->stream($filename);
    }

    return view('payroll::logs.show',$data);
  }

  public function getLogs($users,$dates,$r)
  {
    $logs = [];
    foreach ($users as $key => $u) {
      $payroll = $u->payroll;
      foreach ($payroll as $key1 => $prol) {
        $total_waktu = 0;
        $terlaksana = 0;
        $paid = 0;
        foreach ($dates as $key2 => $d) {
          $nday = $d->format('N');
          $jadwal = $prol->jadwal
          ->when($r->jadwal,function($q,$role){
            $q->whereIn('id',$role);
          })
          ->where('hari','like','%'.$nday.'%')
          ->orderBy('cin','asc')
          ->orderBy('start_cin','asc')
          ->get();

          if (!$jadwal) {
            continue;
          }

          $absen = $u->absen()
          ->where('created_at','>=',$d->startOfDay()->format('Y-m-d H:i:s'))
          ->where('created_at','<=',$d->endOfDay()->format('Y-m-d H:i:s'))
          ->orderBy('created_at','asc')
          ->get();

          foreach ($jadwal as $key3 => $j) {

            $jstart_cin = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->start_cin);
            $jend_cin = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->end_cin);
            $jstart_cout = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->start_cout);
            $jend_cout = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->end_cout);
            $jcin = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->cin);
            $jcout = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->cout);

            $total_waktu += $jcin->diffInMinutes($jcout);

            $cin = null;
            $cout = null;
            foreach ($absen as $ak => $a) {
              if ($a->created_at->greaterThanOrEqualTo($jstart_cin) && $a->created_at->lessThanOrEqualTo($jend_cin) && $a->ruang_id == $j->ruang) {
                if (!$cin) {
                  $cin = $a->created_at->startOfMinute();
                }
              }
              if ($a->created_at->greaterThanOrEqualTo($jstart_cout) && $a->created_at->lessThanOrEqualTo($jend_cout) && $a->ruang_id == $j->ruang) {
                if (!$cout) {
                  $cout = $a->created_at->startOfMinute();
                }
              }
            }

            $realMinutes = $cin&&$cout?$cout->diffInMinutes($cin):0;

            if ($prol->lembur=='N') {
              $realMinutes = $realMinutes>$jcin->diffInMinutes($jcout)?$jcin->diffInMinutes($jcout):$realMinutes;
            }

            $terlaksana += $realMinutes;

            $minutesInHour = $prol->menit??60;
            $hour = (int) floor($realMinutes / $minutesInHour);
            $minute = $realMinutes % $minutesInHour;

            $paid += $hour*$prol->gaji;
            $paid += $minute/$minutesInHour*$prol->gaji;

          }
          $data = [
            'name' => $prol->name,
            'gaji' => $prol->gaji,
            'menit' => $prol->menit,
            'total_waktu' => $total_waktu,
            'terlaksana' => $terlaksana,
            'total_gaji' => $paid,
          ];
          $logs[$key]['name'] = $u->name;
          $logs[$key]['gaji'][$key1] = $data;
        }
      }
    }
    return $logs;
  }
}
