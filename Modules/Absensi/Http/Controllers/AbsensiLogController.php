<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\User;
use Modules\Absensi\Entities\Jadwal;
use Modules\Absensi\Entities\HariLibur;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use GuzzleHttp\Client;
use App\Configs;
use PDF;

class AbsensiLogController extends Controller
{
  public function __construct()
  {
    $this->configs = Configs::getAll();
  }

  public function index()
  {
    $data = [
      'title' => 'Absensi Log',
      'jadwal' => [],
      'users' => []
    ];
    return view('absensi::logs.index',$data);
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
    ->when($r->role,function($q,$role){
      $q->where('role',$role);
    })
    ->orderBy('name','asc')
    ->get();

    if (!count($users)) {
      return redirect()->route('absensi.log.index')->withErrors(['User tidak tersedia']);
    }

    $dates = Carbon::parse($r->start_date)->toPeriod($r->end_date);

    $logs = $this->getLogs($users,$dates,$r);

    $jadwal = [];

    if ($r->jadwal) {
      $jadwal = Jadwal::whereIn('id',$r->jadwal)->get();
    }

    $data = [
      'title' => 'Absensi Log',
      'users' => $users,
      'jadwal' => $jadwal,
      'config' => $this->configs,
      'data' => $logs,
    ];

    if ($r->download_pdf) {
      if (!count($logs)) {
        return redirect()->route('absensi.log.index')->withErrors(['Log absen tidak tersedia!']);
      }

      if ($r->start_date!=$r->end_date) {
        $tgl = Carbon::parse($r->start_date)->locale('id')->translatedFormat('j F Y').' s.d. '.Carbon::parse($r->end_date)->locale('id')->translatedFormat('j F Y');
      }else {
        $tgl = Carbon::parse($r->start_date)->locale('id')->translatedFormat('j F Y');
      }

      if (request()->user && count($users)==1) {
        $data['title'] = ($r->title??'Absensi Log').' - '.$users[0]->name.' ('.$tgl.')';
      }else{
        $data['title'] = ($r->title??'Absensi Log').' ('.$tgl.')';
      }

      $params = [
        'page-width'=>'21.5cm',
        'page-height'=>'33cm',
      ];
      if (!request()->user||count($users)>1) {
        $params['orientation'] = 'landscape';
      }

      $filename = $data['title'].'.pdf';

      $pdf = PDF::loadView('absensi::logs.print',$data)
      ->setOptions($params);
      return $pdf->stream($filename);

      // $view = view('absensi::logs.print',$data)->render();
      // $client = new Client;
      // $params = [
      //   'html'=>str_replace(url('/'),'http://nginx_arsip/',$view),
      //   'options[page-width]'=>'21.5cm',
      //   'options[page-height]'=>'33cm',
      // ];
      // if (!request()->user) {
      //   $params['options[orientation]'] = 'landscape';
      // }
      // $res = $client->request('POST','http://pdf/pdf',[
      //   'form_params'=>$params
      // ]);
      //
      // if ($res->getStatusCode() == 200) {
      //   $filename = $data['title'].'.pdf';
      //   return response()->attachment($res->getBody()->getContents(),$filename,'application/pdf');
      // }
    }

    return view('absensi::logs.show',$data);
  }

  public function getLogs($users,$dates,$r)
  {
    $logs = [];
    foreach ($dates as $key => $d) {
      $nday = $d->format('N');

      $libur = HariLibur::where('start','<=',$d->startOfDay()->format('Y-m-d'))
      ->where('end','>=',$d->startOfDay()->format('Y-m-d'))
      ->orderBy('created_at','asc')
      ->get();

      if (count($libur)) {
        continue;
      }

      foreach ($users as $key1 => $u) {
        $jadwal = $u->jadwal()
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

        foreach ($jadwal as $key2 => $j) {

          $desc = $u->absenDesc()
          ->where('time','<=',$d->startOfDay()->format('Y-m-d H:i:s'))
          ->where('time_end','>=',$d->startOfDay()->format('Y-m-d H:i:s'))
          ->where('jadwal','like','%'.$j->id.'%')
          ->orderBy('created_at','asc')
          ->first();

          $jstart_cin = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->start_cin);
          $jend_cin = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->end_cin);
          $jstart_cout = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->start_cout);
          $jend_cout = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->end_cout);
          $jcin = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->cin);
          $jcout = Carbon::createFromFormat('Y-m-d H:i',$d->format('Y-m-d').' '.$j->cout);

          $cin = null;
          $cout = null;
          $late = 0;
          $early = 0;
          $count = 0;
          $colorCin = '';
          $colorCout = '';
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

          $late = $cin&&$cin->greaterThan($jcin->addMinutes($j->late))?$cin->diffInMinutes($jcin):0;
          $early = $cout&&$cout->lessThan($jcout->subMinutes($j->early))?$jcout->diffInMinutes($cout):0;
          $realMinutes = $cin&&$cout?$cout->diffInMinutes($cin):0;

          $minutesInHour = $j->menit_per_jam??60;
          $hour = (int) floor($realMinutes / $minutesInHour);
          $minute = $realMinutes % $minutesInHour;

          $str = CarbonInterval::fromString("$hour hours $minute minutes")->locale('id')->forHumans();
          $strArr = explode(' ',$str);
          $count = $strArr[0].' '.($j->satuan_jam??' Jam').(isset($strArr[2])?"<br>".$strArr[2].' Menit':null);

          $cin = $cin?$cin->format('H:i'):null;
          $cout = $cout?$cout->format('H:i'):null;

          if ($cin || $jend_cin->lessThanOrEqualTo(Carbon::now())) {
            if ($desc && !$cin) {
              $colorCin = 'bg-success';
            }else{
              $colorCin = $cin?$late?'bg-warning':'':'bg-danger';
            }
          }

          if ($cout || $jend_cout->lessThanOrEqualTo(Carbon::now())) {
            if ($desc && !$cout) {
              $colorCout = 'bg-success';
            }else{
              $colorCout = $cout?$early?'bg-warning':'':'bg-danger';
            }
          }

          $data = [
            'acin'=>$cin,
            'acout'=>$cout,
            'alate'=>$cin?$late.' Menit':null,
            'aearly'=>$cout?$early.' Menit':null,
            'acount'=>$cin&&$cout?$count:null,
            'jadwal'=>$j,
            'colorCin'=>$colorCin,
            'colorCout'=>$colorCout,
            'desc'=>$desc?$desc->desc:null,
          ];
          $logs[$d->format('d/m/Y')][$u->name.'_'.$key1][] = $data;
        }
      }
    }
    return $logs;
  }
}
