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

    if (!$ruang) {
      return response()->json([
        'status'=>'error',
        'message'=>'QR Code tidak terdaftar!',
      ],404);
    }

    $now = Carbon::now();
    $time = $now->format('H:i');
    $hari = $now->format('N');

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
        'message'=>'Jadwal tidak tersedia!',
      ],404);
    }

    $getJadwal = $user->jadwal()
    ->where('hari','like',"%$hari%")
    ->get();

    $jd = [];
    if (count($getJadwal)) {
      foreach ($getJadwal as $j) {
        array_push($jd,[
          'id' => $j->id,
          'uuid' => $j->uuid,
          'name' => $j->nama_jadwal,
          'ruang' => $j->get_ruang->nama_ruang,
          'date' => Carbon::now()->format("Y-m-d"),
          'start_cin' => Carbon::createFromFormat("H:i",$j->cin)->format("H:i"),
          'cin' => $j->cin,
          'cout' => $j->cout,
        ]);
      }
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
