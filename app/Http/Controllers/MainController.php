<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\User;
use Auth;
use Validator;

class MainController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function login()
  {
    $data = [
      'title' => 'Masuk Halaman Admin  - UPTD SMPN 39 Sinjai'
    ];

    return view('login',$data);
  }

  public function loginProcess(Request $r)
  {
    $roles = [
      'username' => 'required',
      'password' => 'required'
    ];

    $messages = [
      'username.required' => 'Username tidak boleh kosong!',
      'password.required' => 'Password tidak boleh kosong!'
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    if (Auth::attempt([
      'username'=>$r->username,
      'password'=>$r->password,
    ],($r->remember?true:false))) {
      return redirect()->back();
    }

    return redirect()->back()->withErrors(['Username atau password tidak sesuai!'])->withInput($r->only('username','remember'));
  }

  public function logout()
  {
    Auth::logout();
    return redirect()->back();
  }

  public function profile()
  {
    $data = [
      'title'=>'Pengaturan Akun',
      'data'=>auth()->user()
    ];

    return view('profile',$data);
  }

  public function profileUpdate(Request $r)
  {
    $roles = [
      'old_password' => 'required'
    ];

    $messages = [
      'old_password.required' => 'Password tidak boleh kosong!'
    ];

    if (\Auth::user()->role == 'admin') {
      $roles['name'] = 'required';
      $roles['username'] = 'required';
      $messages['name.required'] = 'Nama tidak boleh kosong!';
      $messages['username.required'] = 'Username tidak boleh kosong!';
    }

    Validator::make($r->all(),$roles,$messages)->validate();

    $cek = auth()->validate(['id'=>auth()->user()->id,'password'=>$r->old_password]);


    if ($cek) {
      $user = User::where('id',auth()->user()->id)
      ->first();
      if (\Auth::user()->role == 'admin') {
        $user->name = $r->name;
        $user->username = $r->username;
      }
      if ($r->new_password!='') {
        $user->password = bcrypt($r->new_password);
      }
      $user->save();
      return redirect()->back()->withMessage('Profil berhasil diubah');
    }

    return redirect()->back()->withErrors(['Password tidak sesuai!']);
  }
}
