<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\User;
use App\Configs;
use Auth;
use Validator;
use Storage;

class MainController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function __construct()
  {
    $this->configs = Configs::getAll();
  }

  public function sysConf()
  {
    $data = [
      'title'=>'Pengaturan Sistem',
      'config'=>$this->configs
    ];

    return view('config-sistem',$data);
  }

  public function sysConfUpdate(Request $r)
  {
    $filepathLogo1 = null;
    $filepathLogo2 = null;
    $filepathLoginBG = null;

    if ($r->hasFile('login_bg')) {
      $login_bg = $r->file('login_bg');
      $allowed_ext = ['jpg','jpeg','png'];
      $peta_ext = $login_bg->getClientOriginalExtension();

      if ($login_bg->getSize() > (1024*1000)) {
        return redirect()->back()->withErrors('Ukuran File Background Login tidak boleh lebih dari 1MB')->withInput();
      }elseif (!in_array(strtolower($peta_ext),$allowed_ext)) {
        return redirect()->back()->withErrors('File Background Login harus berekstensi jpg, jpeg, atau png')->withInput();
      }

      $getLogo1 = Configs::where('config','login_bg')->first();
      if ($getLogo1) {
        Storage::disk('public')->delete($getLogo1->value);
      }

      $filepathLoginBG = $login_bg->store('file_configs','public');
      $data = [
        'config'=>'login_bg',
        'value'=>$filepathLoginBG
      ];
      Configs::updateOrCreate(['config'=>'login_bg'],$data);
    }

    if ($r->hasFile('logo1')) {
      $logo1 = $r->file('logo1');
      $allowed_ext = ['jpg','jpeg','png'];
      $peta_ext = $logo1->getClientOriginalExtension();

      if ($logo1->getSize() > (1024*1000)) {
        return redirect()->back()->withErrors('Ukuran File Logo 1 tidak boleh lebih dari 1MB')->withInput();
      }elseif (!in_array(strtolower($peta_ext),$allowed_ext)) {
        return redirect()->back()->withErrors('File Logo 1 harus berekstensi jpg, jpeg, atau png')->withInput();
      }

      $getLogo1 = Configs::where('config','logo1')->first();
      if ($getLogo1) {
        Storage::disk('public')->delete($getLogo1->value);
      }

      $filepathLogo1 = $logo1->store('file_configs','public');
      $data = [
        'config'=>'logo1',
        'value'=>$filepathLogo1
      ];
      Configs::updateOrCreate(['config'=>'logo1'],$data);
    }

    if ($r->hasFile('logo2')) {
      $logo2 = $r->file('logo2');
      $allowed_ext = ['jpg','jpeg','png'];
      $peta_ext = $logo2->getClientOriginalExtension();

      if ($logo2->getSize() > (1024*1000)) {
        return redirect()->back()->withErrors('Ukuran File Logo 2 tidak boleh lebih dari 1MB')->withInput();
      }elseif (!in_array(strtolower($peta_ext),$allowed_ext)) {
        return redirect()->back()->withErrors('File Logo 2 harus berekstensi jpg, jpeg, atau png')->withInput();
      }
      $getLogo2 = Configs::where('config','logo2')->first();
      if ($getLogo2) {
        Storage::disk('public')->delete($getLogo2->value);
      }

      $filepathLogo2 = $logo2->store('file_configs','public');
      $data = [
        'config'=>'logo2',
        'value'=>$filepathLogo2
      ];
      Configs::updateOrCreate(['config'=>'logo2'],$data);
    }

    $insert = null;
    foreach ($r->config as $key => $value) {
      $data = [
        'config'=>$key,
        'value'=>$value
      ];
      $insert = Configs::updateOrCreate(['config'=>$key],$data);
    }

    if (!$r->rnd_act_key) {
      $data = [
        'config'=>'act_key',
        'value'=>$r->act_key
      ];
      $insert = Configs::updateOrCreate(['config'=>'act_key'],$data);
    }else {
      Configs::where('config','act_key')->update(['value'=>null]);
    }

    if ($insert) {
      return redirect()->route('configs')->with('message','Data berhasil disimpan');
    }
  }

  public function login()
  {
    $data = [
      'config'=>$this->configs,
      'title' => 'Masuk Halaman Admin  - '.(@$this->config->nama_instansi??'UPTD SMP NEGERI 39 SINJAI')
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

  public function downloadAPP()
  {
    return response()->download(public_path('assets/app/absen_digital.apk'));
  }
}
