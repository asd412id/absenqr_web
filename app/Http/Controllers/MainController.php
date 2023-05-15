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
    protected $configs;

    public function __construct()
    {
        $this->configs = Configs::getAll();
    }

    public function sysConf()
    {
        $data = [
            'title' => 'Pengaturan Sistem',
            'config' => $this->configs
        ];

        return view('config-sistem', $data);
    }

    public function sysConfUpdate(Request $r)
    {
        $filepathLogo = null;
        $filepathKop = null;
        $filepathLoginBG = null;

        if ($r->hasFile('login_bg')) {
            $login_bg = $r->file('login_bg');
            $allowed_ext = ['jpg', 'jpeg', 'png'];
            $peta_ext = $login_bg->getClientOriginalExtension();

            if ($login_bg->getSize() > (1024 * 1000)) {
                return redirect()->back()->withErrors('Ukuran File Background Login tidak boleh lebih dari 1MB')->withInput();
            } elseif (!in_array(strtolower($peta_ext), $allowed_ext)) {
                return redirect()->back()->withErrors('File Background Login harus berekstensi jpg, jpeg, atau png')->withInput();
            }

            $getLogo = Configs::where('config', 'login_bg')->first();
            if ($getLogo) {
                Storage::disk('public')->delete($getLogo->value);
            }

            $filepathLoginBG = $login_bg->store('file_configs', 'public');
            $data = [
                'config' => 'login_bg',
                'value' => $filepathLoginBG
            ];
            Configs::updateOrCreate(['config' => 'login_bg'], $data);
        }

        if ($r->hasFile('logo')) {
            $logo = $r->file('logo');
            $allowed_ext = ['jpg', 'jpeg', 'png'];
            $peta_ext = $logo->getClientOriginalExtension();

            if ($logo->getSize() > (1024 * 1000)) {
                return redirect()->back()->withErrors('Ukuran File Logo 1 tidak boleh lebih dari 1MB')->withInput();
            } elseif (!in_array(strtolower($peta_ext), $allowed_ext)) {
                return redirect()->back()->withErrors('File Logo 1 harus berekstensi jpg, jpeg, atau png')->withInput();
            }

            $getLogo = Configs::where('config', 'logo')->first();
            if ($getLogo) {
                Storage::disk('public')->delete($getLogo->value);
            }

            $filepathLogo = $logo->store('file_configs', 'public');
            $data = [
                'config' => 'logo',
                'value' => $filepathLogo
            ];
            Configs::updateOrCreate(['config' => 'logo'], $data);
        }

        if ($r->hasFile('kop')) {
            $kop = $r->file('kop');
            $allowed_ext = ['jpg', 'jpeg', 'png'];
            $peta_ext = $kop->getClientOriginalExtension();

            if ($kop->getSize() > (1024 * 1000)) {
                return redirect()->back()->withErrors('Ukuran File Logo 2 tidak boleh lebih dari 1MB')->withInput();
            } elseif (!in_array(strtolower($peta_ext), $allowed_ext)) {
                return redirect()->back()->withErrors('File Logo 2 harus berekstensi jpg, jpeg, atau png')->withInput();
            }
            $getkop = Configs::where('config', 'kop')->first();
            if ($getkop) {
                Storage::disk('public')->delete($getkop->value);
            }

            $filepathKop = $kop->store('file_configs', 'public');
            $data = [
                'config' => 'kop',
                'value' => $filepathKop
            ];
            Configs::updateOrCreate(['config' => 'kop'], $data);
        }

        $insert = null;
        foreach ($r->config as $key => $value) {
            $data = [
                'config' => $key,
                'value' => $value
            ];
            $insert = Configs::updateOrCreate(['config' => $key], $data);
        }

        if (!$r->rnd_act_key) {
            $data = [
                'config' => 'act_key',
                'value' => $r->act_key
            ];
            $insert = Configs::updateOrCreate(['config' => 'act_key'], $data);
        } else {
            Configs::where('config', 'act_key')->update(['value' => null]);
        }

        if ($insert) {
            return redirect()->route('configs')->with('message', 'Data berhasil disimpan');
        }
    }

    public function login()
    {
        $configs = $this->configs;
        if (@$configs->logo) {
            $logo = asset('uploaded/' . @$configs->logo);
        } else {
            $logo = url('assets/img/sinjai.png');
        }

        $data = [
            'config' => $this->configs,
            'logo' => $logo,
            'title' => 'Masuk Halaman Admin  - ' . (@$this->configs->nama_instansi ?? 'UPTD SMP NEGERI 39 SINJAI')
        ];

        return view('login', $data);
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

        Validator::make($r->all(), $roles, $messages)->validate();

        if (Auth::attempt([
            'username' => $r->username,
            'password' => $r->password,
        ], ($r->remember ? true : false))) {
            return redirect()->back();
        }

        return redirect()->back()->withErrors(['Username atau password tidak sesuai!'])->withInput($r->only('username', 'remember'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->back();
    }

    public function profile()
    {
        $data = [
            'title' => 'Pengaturan Akun',
            'data' => auth()->user()
        ];

        return view('profile', $data);
    }

    public function deleteImg($img)
    {
        $config = Configs::where('config', $img)->first();
        if ($config && $config->value) {
            Storage::disk('public')->delete($config->value);
            if ($config->delete()) {
                return redirect()->route('configs')->withMessage('Logo berhasil dihapus!');
            }
        }
        return redirect()->back()->withErrors(['Logo gagal dihapus!']);
    }

    public function profileUpdate(Request $r)
    {
        $roles = [
            'old_password' => 'required'
        ];

        $messages = [
            'old_password.required' => 'Password tidak boleh kosong!'
        ];

        // if (\Auth::user()->role == 'admin') {
        $roles['name'] = 'required';
        $roles['username'] = 'required|unique:users,username,' . auth()->user()->uuid . ',uuid';
        $messages['name.required'] = 'Nama tidak boleh kosong!';
        $messages['username.required'] = 'Username tidak boleh kosong!';
        $messages['username.unique'] = 'Username telah digunakan!';
        // }

        Validator::make($r->all(), $roles, $messages)->validate();

        $cek = auth()->validate(['id' => auth()->user()->id, 'password' => $r->old_password]);

        if ($cek) {
            $user = User::where('id', auth()->user()->id)
                ->first();
            if (\Auth::user()->role == 'admin') {
                $user->name = $r->name;
                $user->username = $r->username;
            }
            if ($r->new_password != '') {
                $user->password = bcrypt($r->new_password);
            }
            $user->save();
            return redirect()->back()->withMessage('Profil berhasil diubah');
        }

        return redirect()->back()->withErrors(['Password tidak sesuai!']);
    }

    public function downloadAPP()
    {
        return response()->download(public_path('assets/app/app-release.apk'), 'Absen Digital.apk');
    }
}
