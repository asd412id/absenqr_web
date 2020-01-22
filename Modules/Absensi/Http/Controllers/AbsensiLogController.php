<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\User;
use Modules\Absensi\Entities\Jadwal;

use Carbon\Carbon;
use GuzzleHttp\Client;

class AbsensiLogController extends Controller
{
  public function index()
  {
    $user = User::where('role','!=','admin')->get();
    $jadwal = Jadwal::has('user')->get();
    $data = [
      'title' => 'Absensi Log',
      'users' => $user,
      'jadwal' => $jadwal,
    ];
    return view('absensi::logs.index',$data);
  }

  public function showLogs(Request $r)
  {
    if (!$r->jadwal) {
      return redirect()->route('absensi.log.index')->withErrors(['Jadwal harus dipilih']);
    }
    $users = User::where('role','!=','admin')
    ->when($r->status,function($q,$role){
      $q->whereHas('pegawai',function($q) use($role){
        $q->where('status_kepegawaian',$role);
      });
    })
    ->when($r->user,function($q,$role){
      $q->where('uuid',$role);
    })
    ->when($r->role,function($q,$role){
      $q->where('role',$role);
    })
    ->get();

    if (!count($users)) {
      return redirect()->route('absensi.log.index')->withErrors(['User tidak tersedia']);
    }

    $dates = Carbon::parse($r->start_date)->toPeriod($r->end_date);

    $logs = $this->getLogs($users,$dates,$r);

    $user = User::where('role','!=','admin')->get();
    $jadwal = Jadwal::has('user')->get();
    $data = [
      'title' => 'Absensi Log',
      'users' => $user,
      'jadwal' => $jadwal,
      'data' => $logs,
    ];

    if ($r->download_pdf) {
      if (!count($logs)) {
        return redirect()->route('absensi.log.index')->withErrors(['Log absen tidak tersedia!']);
      }
      if (request()->user) {
        $data['title'] = 'Absensi Log - '.$users[0]->name.' ('.Carbon::now()->locale('id')->translatedFormat('j F Y').')';
      }else{
        $data['title'] = 'Absensi Log ('.Carbon::now()->locale('id')->translatedFormat('j F Y').')';
      }
      $view = view('absensi::logs.print',$data)->render();
      $client = new Client;
      $params = [
        'html'=>str_replace(url('/'),'http://nginx_arsip/',$view),
        'options[page-width]'=>'21.5cm',
        'options[page-height]'=>'33cm',
      ];
      if (!request()->user) {
        $params['options[orientation]'] = 'landscape';
      }
      $res = $client->request('POST','http://pdf/pdf',[
        'form_params'=>$params
      ]);

      if ($res->getStatusCode() == 200) {
        $filename = $data['title'].'.pdf';
        return response()->attachment($res->getBody()->getContents(),$filename,'application/pdf');
      }
    }

    return view('absensi::logs.show',$data);
  }

  public function getLogs($users,$dates,$r)
  {
    $logs = [];
    foreach ($users as $key => $u) {

      foreach ($dates as $key => $d) {
        $nday = $d->format('N');
        $jadwal = $u->jadwal()
        ->when($r->jadwal,function($q,$role){
          $q->whereIn('uuid',$role);
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

        foreach ($jadwal as $key => $j) {

          $desc = $u->absenDesc()
          ->where('time',$d->startOfDay()->format('Y-m-d H:i:s'))
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
          $count = $cin&&$cout?$cout->diffInHours($cin):0;

          $cin = $cin?$cin->format('H:i'):null;
          $cout = $cout?$cout->format('H:i'):null;

          if ($cin || $jend_cin->lessThanOrEqualTo(Carbon::now())) {
            $colorCin = $cin?$late?'bg-warning':'':'bg-danger';
          }

          if ($cout || $jend_cout->lessThanOrEqualTo(Carbon::now())) {
            $colorCout = $cout?$early?'bg-warning':'':'bg-danger';
          }

          $data = [
            'acin'=>$cin,
            'acout'=>$cout,
            'alate'=>$cin?$late.' Menit':null,
            'aearly'=>$cout?$early.' Menit':null,
            'acount'=>$cin&&$cout?$count.' Jam':null,
            'jadwal'=>$j,
            'colorCin'=>$colorCin,
            'colorCout'=>$colorCout,
            'desc'=>$desc?$desc->desc:null,
          ];
          $logs[$u->name][$d->format('d/m/Y')][] = $data;
        }
      }
    }
    return $logs;
  }
}
