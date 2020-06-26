<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\HariLibur;

use DataTables;
use Validator;
use Str;
use Carbon\Carbon;

class HariLiburController extends Controller
{
  public function index()
  {
    if (request()->ajax()) {
      $data = HariLibur::orderBy('start','desc')
      ->orderBy('updated_at','desc');
      return DataTables::of($data)
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('absensi.libur.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('absensi.libur.destroy',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus keterangan '.$row->name.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
    }

    $data = ['title'=>'Pengaturan Hari Libur'];

    return view('absensi::libur.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create(Request $r)
  {
    $data = [
      'title'=>'Tambah Hari Libur',
    ];
    return view('absensi::libur.create',$data);
  }

  /**
  * Store a newly created resource in storage.
  * @param Request $request
  * @return Response
  */
  public function store(Request $request)
  {
    $role = [
      'name' => 'required',
      'start' => 'required',
      'end' => 'required',
      'desc' => 'required',
    ];
    $msgs = [
      'name.required' => 'Nama hari libur tidak boleh kosong!',
      'start.required' => 'Tanggal mulai tidak boleh kosong!',
      'end.required' => 'Tanggal selesai tidak boleh kosong!',
      'desc.required' => 'Keterangan hari libur tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = HariLibur::where('name',$request->name)
    ->where('start',$request->start)
    ->where('end',$request->end)
    ->first();

    if (!$insert) {
      $insert = new HariLibur;
      $insert->uuid = (string) Str::uuid();
    }

    $insert->name = $request->name;
    $insert->start = $request->start;
    $insert->end = $request->end;
    $insert->desc = $request->desc;

    if ($insert->save()) {
      return redirect()->route('absensi.libur.index')->with('message','Data berhasil disimpan!');
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
    $libur = HariLibur::where('uuid',$uuid)->first();
    $data = [
      'title'=>'Ubah Hari Libur',
      'data'=>$libur
    ];
    return view('absensi::libur.edit',$data);
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
      'name' => 'required',
      'start' => 'required',
      'end' => 'required',
      'desc' => 'required',
    ];
    $msgs = [
      'name.required' => 'Nama hari libur tidak boleh kosong!',
      'start.required' => 'Tanggal mulai tidak boleh kosong!',
      'end.required' => 'Tanggal selesai tidak boleh kosong!',
      'desc.required' => 'Keterangan hari libur tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = HariLibur::where('uuid',$uuid)->first();
    $insert->name = $request->name;
    $insert->start = $request->start;
    $insert->end = $request->end;
    $insert->desc = $request->desc;

    if ($insert->save()) {
      return redirect()->route('absensi.libur.index')->with('message','Data berhasil disimpan!');
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
    $libur = HariLibur::where('uuid',$uuid)->first();
    if ($libur->delete()) {
      return redirect()->route('absensi.libur.index')->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }
}
