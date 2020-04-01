<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\User;

class AjaxController extends BaseController
{

  public function searchUser(Request $r)
  {
    $data['results'] = [];
    $users = User::when($r->term,function($q,$role){
      $search = "%".$role."%";
      $q->where('name','like',$search);
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

}
