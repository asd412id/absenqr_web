<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\User;
use Modules\Absensi\Entities\Jadwal;
use Modules\Absensi\Entities\Ruang;

class AjaxController extends BaseController
{

  public function searchUser(Request $r)
  {
    if ($r->ajax()) {
      $data['results'] = [];
      $users = User::when($r->term,function($q,$role){
        $search = "%".$role."%";
        $q->where('name','like',$search)
        ->orWhere('role','like',$search)
        ->orWhereHas('pegawai',function($q) use($search){
          $q->where('status_kepegawaian','like',$search)
          ->orWhere('nip','like',$search);
        })
        ->orWhereHas('siswa',function($q) use($search){
          $q->where('kelas','like',$search)
          ->orWhere('nis','like',$search);
        });
      })
      ->where('role','!=','admin')
      ->select('id','name','role')
      ->orderBy('name','asc')
      ->get();
      if (count($users)) {
        foreach ($users as $key => $u) {
          array_push($data['results'],[
            'id' => $u->id,
            'text' => $u->name
          ]);
        }
      }

      return response()->json($data);
    }

    return response()->json([
      'status'=>'error',
      'message'=>'Page not Found'
    ],404);
  }

  public function searchPegawai(Request $r)
  {
    if ($r->ajax()) {
      $data['results'] = [];
      $users = User::when($r->term,function($q,$role){
        $search = "%".$role."%";
        $q->where('name','like',$search)
        ->orWhereHas('pegawai',function($q) use($search){
          $q->where('status_kepegawaian','like',$search)
          ->orWhere('nip','like',$search);
        });
      })
      ->where('role','pegawai')
      ->select('id','name','role')
      ->orderBy('name','asc')
      ->get();
      if (count($users)) {
        foreach ($users as $key => $u) {
          array_push($data['results'],[
            'id' => $u->id,
            'text' => $u->name
          ]);
        }
      }

      return response()->json($data);
    }

    return response()->json([
      'status'=>'error',
      'message'=>'Page not Found'
    ],404);
  }

  public function searchRuang(Request $r)
  {
    if ($r->ajax()) {
      $data['results'] = [];
      $ruang = Ruang::when($r->term,function($q,$role){
        $search = "%".$role."%";
        $q->where('nama_ruang','like',$search)
        ->orWhere('desc','like',$search);
      })
      ->orderBy('nama_ruang','asc')
      ->get();
      if (count($ruang)) {
        foreach ($ruang as $key => $u) {
          array_push($data['results'],[
            'id' => $u->id,
            'text' => $u->nama_ruang
          ]);
        }
      }

      return response()->json($data);
    }

    return response()->json([
      'status'=>'error',
      'message'=>'Page not Found'
    ],404);
  }

  public function searchJadwal(Request $r)
  {
    if ($r->ajax()) {
      $data['results'] = [];
      $jadwal = Jadwal::when($r->term,function($q,$role){
        $search = "%".$role."%";
        $q->where('nama_jadwal','like',$search)
        ->orWhere('alias','like',$search)
        ->orWhereHas('get_ruang',function($q) use($search){
          $q->where('nama_ruang','like',$search);
        });
      })
      ->orderBy('nama_jadwal','asc')
      ->get();
      if (count($jadwal)) {
        foreach ($jadwal as $key => $u) {
          array_push($data['results'],[
            'id' => $u->id,
            'text' => $u->nama_jadwal.($u->alias?' ('.$u->alias.')':'').' - '.$u->get_ruang->nama_ruang
          ]);
        }
      }

      return response()->json($data);
    }

    return response()->json([
      'status'=>'error',
      'message'=>'Page not Found'
    ],404);
  }

}
