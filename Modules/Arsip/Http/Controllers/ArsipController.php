<?php

namespace Modules\Arsip\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Arsip\Entities\Siswa;
use Modules\Arsip\Entities\Pegawai;

class ArsipController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    $data = [
      'siswa'=>Siswa::count(),
      'pegawai'=>Pegawai::count(),
      'pns'=>Pegawai::where('status_kepegawaian','pns')->count(),
      'gtt'=>Pegawai::where('status_kepegawaian','gtt')->count(),
    ];
    return view('arsip::index',$data);
  }
}
