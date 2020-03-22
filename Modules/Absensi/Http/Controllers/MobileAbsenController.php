<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\Ruang;

use Carbon\Carbon;

class MobileAbsenController extends Controller
{
  public function absenCheck(Request $r)
  {
    $ruang_token = $r->header('Ruang-Token');

    $user = auth()->user();
    $ruang = Ruang::where('_token',$ruang_token)->first();

    $now = Carbon::now();
    $time = $now->format('H:i');
    $hari = $now->format('N');

    $getJadwal = $user->jadwal()
    ->where('hari','like',"%$hari%")
    ->where('cin','>',$time)
    ->get();

    $jd = [];
    if (count($getJadwal)) {
      foreach ($getJadwal as $j) {
        $time5 = Carbon::createFromFormat("H:i",$j->cin)->subMinutes(5)->format("H:i:00");
        array_push($jd,[
          'id' => $j->id,
          'uuid' => $j->uuid,
          'ruang' => $j->get_ruang->nama_ruang.' (5 menit lagi)',
          'name' => $j->nama_jadwal.' - '.$j->cin,
          'date' => Carbon::now()->format("Y-m-d"),
          'start_cin' => Carbon::createFromFormat("Y-m-d H:i:s",$now->format("Y-m-d ").$time5)->timestamp*1000,
          'cin' => $j->cin,
          'cout' => $j->cout,
        ]);
      }
    }

    if (!$ruang) {
      return response()->json([
        'status'=>'error',
        'jadwal'=>$jd,
        'message'=>'QR Code tidak terdaftar!',
      ],404);
    }

    $jadwal = $user->jadwal()
    ->where('ruang',$ruang->id)
    ->where('hari','like',"%$hari%")
    ->where(function($q) use($time){
      $q->where(function($q) use($time){
        $q->where('start_cin','<=',$time)
        ->where('end_cin','>=',$time);
      })
      ->orWhere(function($q) use($time){
        $q->where('start_cout','<=',$time)
        ->where('end_cout','>=',$time);
      });
    })
    ->count();

    if (!$jadwal) {
      return response()->json([
        'status'=>'error',
        'jadwal'=>$jd,
        'message'=>'Jadwal tidak tersedia!',
      ],404);
    }

    $user->absenRuang()->attach($ruang->id);
    $absen = $user->absen->last();

    return response()->json([
      'status'=>'success',
      'message'=>'Absen Berhasil',
      'ruang'=>$ruang->nama_ruang,
      'jadwal'=>$jd,
      'time'=>$absen->created_at->format('H:i')
    ],202);
  }
}
