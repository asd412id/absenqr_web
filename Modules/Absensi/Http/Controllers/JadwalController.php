<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\Jadwal;
use Modules\Absensi\Entities\Ruang;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;

class JadwalController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    if (request()->ajax()) {
      $data = Jadwal::with('get_ruang');
      return DataTables::of($data)
      ->addColumn('nama_hari',function($row){
        return implode(", ",$row->nama_hari);
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('absensi.jadwal.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('absensi.jadwal.destroy',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus data '.$row->nama_jadwal.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
    }

    $data = ['title'=>'Jadwal Absensi'];

    return view('absensi::jadwal.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create()
  {
    $data = [
      'title'=>'Tambah Jadwal Absensi',
      'ruang'=>Ruang::all(),
    ];
    return view('absensi::jadwal.create',$data);
  }

  /**
  * Store a newly created resource in storage.
  * @param Request $request
  * @return Response
  */
  public function store(Request $request)
  {
    $role = [
      'nama_jadwal' => 'required',
      'ruang' => 'required',
      'cin' => 'required',
      'cout' => 'required',
      'late' => 'required',
      'early' => 'required',
      'start_cin' => 'required',
      'end_cin' => 'required',
      'start_cout' => 'required',
      'end_cout' => 'required',
      'hari' => 'required',
    ];
    $msgs = [
      'nama_jadwal.required' => 'Nama jadwal absensi tidak boleh kosong!',
      'ruang.required' => 'Nama jadwal absensi tidak boleh kosong!',
      'cin.required' => 'Check In tidak boleh kosong!',
      'cout.required' => 'Check Out tidak boleh kosong!',
      'late.required' => 'Terlambat tidak boleh kosong!',
      'early.required' => 'Pulang Cepat tidak boleh kosong!',
      'start_cin.required' => 'Mulai Check In tidak boleh kosong!',
      'end_cin.required' => 'Selesai Check In tidak boleh kosong!',
      'start_cout.required' => 'Mulai Check Out tidak boleh kosong!',
      'end_cout.required' => 'Selesai Check Out tidak boleh kosong!',
      'hari.required' => 'Hari tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = new Jadwal;
    $insert->uuid = (string) Str::uuid();
    $insert->nama_jadwal = $request->nama_jadwal;
    $insert->ruang = $request->ruang;
    $insert->hari = $request->hari;
    $insert->cin = $request->cin;
    $insert->cout = $request->cout;
    $insert->start_cin = $request->start_cin;
    $insert->end_cin = $request->end_cin;
    $insert->start_cout = $request->start_cout;
    $insert->end_cout = $request->end_cout;
    $insert->late = $request->late;
    $insert->early = $request->early;

    if ($insert->save()) {
      return redirect()->route('absensi.jadwal.index')->with('message','Data berhasil disimpan!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  /**
  * Show the form for editing the specified resource.
  * @param int $uuid
  * @return Response
  */
  public function edit($uuid)
  {
    $jadwal = Jadwal::where('uuid',$uuid)->first();
    if (!$jadwal) {
      return redirect()->route('absensi.jadwal.index');
    }
    $data = [
      'title'=>'Ubah Jadwal Absensi',
      'ruang'=>Ruang::all(),
      'data'=>$jadwal
    ];
    return view('absensi::jadwal.edit',$data);
  }

  /**
  * Update the specified resource in storage.
  * @param Request $request
  * @param int $uuid
  * @return Response
  */
  public function update(Request $request, $uuid)
  {
    $role = [
      'nama_jadwal' => 'required',
      'ruang' => 'required',
      'cin' => 'required',
      'cout' => 'required',
      'late' => 'required',
      'early' => 'required',
      'start_cin' => 'required',
      'end_cin' => 'required',
      'start_cout' => 'required',
      'end_cout' => 'required',
      'hari' => 'required',
    ];
    $msgs = [
      'nama_jadwal.required' => 'Nama jadwal absensi tidak boleh kosong!',
      'ruang.required' => 'Nama jadwal absensi tidak boleh kosong!',
      'cin.required' => 'Check In tidak boleh kosong!',
      'cout.required' => 'Check Out tidak boleh kosong!',
      'late.required' => 'Terlambat tidak boleh kosong!',
      'early.required' => 'Pulang Cepat tidak boleh kosong!',
      'start_cin.required' => 'Mulai Check In tidak boleh kosong!',
      'end_cin.required' => 'Selesai Check In tidak boleh kosong!',
      'start_cout.required' => 'Mulai Check Out tidak boleh kosong!',
      'end_cout.required' => 'Selesai Check Out tidak boleh kosong!',
      'hari.required' => 'Hari tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = Jadwal::where('uuid',$uuid)->first();
    $insert->nama_jadwal = $request->nama_jadwal;
    $insert->ruang = $request->ruang;
    $insert->hari = $request->hari;
    $insert->cin = $request->cin;
    $insert->cout = $request->cout;
    $insert->start_cin = $request->start_cin;
    $insert->end_cin = $request->end_cin;
    $insert->start_cout = $request->start_cout;
    $insert->end_cout = $request->end_cout;
    $insert->late = $request->late;
    $insert->early = $request->early;

    if ($insert->save()) {
      return redirect()->route('absensi.jadwal.index')->with('message','Data berhasil disimpan!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  /**
  * Remove the specified resource from storage.
  * @param int $uuid
  * @return Response
  */
  public function destroy($uuid)
  {
    $jadwal = Jadwal::where('uuid',$uuid)->first();
    if ($jadwal->delete()) {
      return redirect()->route('absensi.jadwal.index')->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }
}
