<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\Ruang;
use Modules\Absensi\Entities\Jadwal;
use Modules\Absensi\Entities\AbsensiLogs;
use Modules\Absensi\Entities\AbsensiDesc;

class AbsensiController extends Controller
{
  public function index()
  {
    $data = [
      'ruang'=>Ruang::count(),
      'jadwal'=>Jadwal::count(),
      'logs'=>AbsensiLogs::count(),
      'desc'=>AbsensiDesc::count(),
    ];
    return view('absensi::index',$data);
  }
}
