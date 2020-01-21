<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\AbsensiDesc;
use App\User;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;

class AbsensiDescController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    if (request()->ajax()) {
      $data = AbsensiDesc::with('user');
      return DataTables::of($data)
      ->addColumn('get_time',function($row){
        return $row->time->format('d-m-Y');
      })
      ->addColumn('get_desc',function($row){
        return nl2br($row->desc);
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('absensi.desc.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('absensi.desc.destroy',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus keterangan '.$row->user->name.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action','get_desc'])
      ->make(true);
    }

    $data = ['title'=>'Keterangan Absensi'];

    return view('absensi::desc.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create()
  {
    $data = [
      'title'=>'Tambah Keterangan Absensi',
      'user'=>User::where('role','!=','admin')->get(),
    ];
    return view('absensi::desc.create',$data);
  }

  /**
  * Store a newly created resource in storage.
  * @param Request $request
  * @return Response
  */
  public function store(Request $request)
  {
    $role = [
      'user' => 'required',
      'time' => 'required',
      'desc' => 'required',
    ];
    $msgs = [
      'user.required' => 'User tidak boleh kosong!',
      'time.required' => 'Waktu tidak boleh kosong!',
      'desc.required' => 'Keterangan tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = new AbsensiDesc;
    $insert->uuid = (string) Str::uuid();
    $insert->user_id = $request->user;
    $insert->time = $request->time;
    $insert->desc = $request->desc;

    if ($insert->save()) {
      return redirect()->route('absensi.desc.index')->with('message','Data berhasil disimpan!');
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
    $desc = AbsensiDesc::where('uuid',$uuid)->first();
    if (!$desc) {
      return redirect()->route('absensi.desc.index');
    }
    $data = [
      'title'=>'Ubah Keterangan Absensi',
      'user'=>User::where('role','!=','admin')->get(),
      'data'=>$desc
    ];
    return view('absensi::desc.edit',$data);
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
      'time' => 'required',
      'desc' => 'required',
    ];
    $msgs = [
      'time.required' => 'Waktu tidak boleh kosong!',
      'desc.required' => 'Keterangan tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = AbsensiDesc::where('uuid',$uuid)->first();
    $insert->time = $request->time;
    $insert->desc = $request->desc;

    if ($insert->save()) {
      return redirect()->route('absensi.desc.index')->with('message','Data berhasil disimpan!');
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
    $desc = AbsensiDesc::where('uuid',$uuid)->first();
    if ($desc->delete()) {
      return redirect()->route('absensi.desc.index')->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }
}
