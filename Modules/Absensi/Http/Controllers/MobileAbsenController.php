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
        'message'=>'QRCode tidak terdaftar!',
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

    $user->absenRuang()->attach($ruang->id);
    $absen = $user->absen->last();

    return response()->json([
      'status'=>'success',
      'message'=>'Absen Berhasil',
      'ruang'=>$ruang->nama_ruang,
      'time'=>$absen->created_at->format('H:i')
    ],202);
  }
}
