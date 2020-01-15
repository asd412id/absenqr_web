<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\User;
use Auth;
use Str;

class MobileController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function checkServer(Request $r)
  {
    return response()->json(['status'=>'connected'],302);
  }

  public function login(Request $r)
  {
    $username = $r->header('username');
    $password = $r->header('password');

    if (Auth::attempt([
      'username'=>$username,
      'password'=>$password,
    ])) {
      $user = auth()->user();
      if ($user->role == 'admin') {
        return response()->json([
          'status'=>'error',
          'error'=>'login',
          'message'=>'Username atau password tidak benar'
        ],401);
      }
      $user->activate_key = mt_rand(100000,999999);
      $user->api_token = Str::random(100);
      $user->active = false;
      $user->changed_password = false;
      $user->save();
      return response()->json([
        'status'=>'success',
        'message'=>'Login berhasil',
        '_token'=>$user->api_token,
        'data'=>$user
      ],202);
    }

    return response()->json([
      'status'=>'error',
      'error'=>'login',
      'message'=>'Username atau password tidak benar'
    ],401);
  }

  public function activate(Request $r)
  {
    $key = $r->header('Activate-Key');
    $user = auth()->user();

    if ($user->activate_key == $key) {
      $user->active = true;
      $user->save();
      return response()->json([
        'status'=>'success',
        'message'=>'Aktivasi perangkat berhasil. Silahkan ganti password Anda!',
        'data'=>$user
      ],202);
    }
    return response()->json([
      'status'=>'error',
      'error'=>'activate_key',
      'message'=>'Kode aktivasi tidak benar'
    ],401);
  }

  public function changePassword(Request $r)
  {
    $password = $r->header('New-Password');
    $user = auth()->user();
    $user->password = bcrypt($password);
    $user->changed_password = true;
    $user->save();
    return response()->json([
      'status'=>'success',
      'message'=>'Password berhasil diubah'
    ],201);
  }

  public function logout()
  {
    $user = auth()->user();
    $user->api_token = null;
    if ($user->save()) {
      return response()->json([
        'status'=>'success',
        'message'=>'Anda berhasil logout'
      ],202);
    }
    return response()->json([
      'status'=>'error',
      'message'=>'Logout error'
    ],500);
  }

  public function absenCheck(Request $r)
  {
    
  }

}
