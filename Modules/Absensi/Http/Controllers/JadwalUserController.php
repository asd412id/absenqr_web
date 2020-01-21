<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\User;
use Modules\Absensi\Entities\Jadwal;

use DataTables;

class JadwalUserController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    if (request()->ajax()) {
      $data = User::with('jadwal')->where('role','!=','admin');
      return DataTables::of($data)
      ->addColumn('jadwal',function($row){
        $jd = [];
        if (count($row->jadwal)) {
          foreach ($row->jadwal as $key => $j) {
            array_push($jd,'<span class="badge badge-primary">'.$j->nama_jadwal.' ('.$j->get_ruang->nama_ruang.')</span>');
          }
        }
        return count($jd)?implode(" ",$jd):'-';
      })
      ->addColumn('role_text',function($row){
        return ucwords($row->role);
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('absensi.jadwal.user.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('absensi.jadwal.user.reset',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Reset Jadwal '.$row->name.'?" title="Reset Jadwal"><i class="ik ik-refresh-cw"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action','jadwal'])
      ->make(true);
    }

    $data = ['title'=>'Jadwal Absen User'];

    return view('absensi::jadwal-user.index',$data);
  }

  public function edit($uuid)
  {
    $user = User::where('uuid',$uuid)->first();
    $data = [
      'title'=>'Ubah Jadwal Absen',
      'data'=>$user,
      'jadwal'=>Jadwal::all(),
    ];

    return view('absensi::jadwal-user.edit',$data);
  }

  public function update($uuid, Request $request)
  {
    $user = User::where('uuid',$uuid)->first();
    $user->jadwal()->sync($request->jadwal_user);
    return redirect()->route('absensi.jadwal.user.index')->with('message','Data berhasil disimpan!');
  }

  public function reset($uuid)
  {
    $user = User::where('uuid',$uuid)->first();
    $user->jadwal()->detach();
    return redirect()->route('absensi.jadwal.user.index')->with('message','Data berhasil direset!');
  }

}
