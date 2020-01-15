<?php

namespace Modules\Arsip\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Arsip\Entities\Pegawai;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;
use Carbon\Carbon;

class PegawaiController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    if (request()->ajax()) {
      $data = Pegawai::query();
      return DataTables::of($data)
      ->addColumn('jk',function($row){
        return $row->jenis_kelamin==1?'Laki - Laki':'Perempuan';
      })
      ->addColumn('skep',function($row){
        return strtoupper($row->status_kepegawaian);
      })
      ->addColumn('activate_key',function($row){
        $data = $row->user->activate_key??'-';
        return $data;
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        $btn .= '<a href="'.route('pegawai.export.single.pdf',['uuid'=>$row->uuid]).'" class="text-danger" target="_blank" title="Ekspor PDF"><i class="fas fa-file-pdf"></i></a>';

        $btn .= '<a href="'.route('pegawai.show',['uuid'=>$row->uuid]).'" class="text-success" title="Detail"><i class="ik ik-info"></i></a>';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('pegawai.reset.login',['uuid'=>$row->uuid]).'" class="text-warning confirm" data-text="Reset login '.$row->nama.'?" title="Reset Login"><i class="ik ik-refresh-cw"></i></a>';

          $btn .= ' <a href="'.route('pegawai.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('pegawai.destroy',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus data '.$row->nama.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
    }

    $data = ['title'=>'Data Guru & Pegawai'];

    return view('arsip::pegawai.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create()
  {
    $data = ['title'=>'Tambah Pegawai'];
    return view('arsip::pegawai.create',$data);
  }

  /**
  * Store a newly created resource in storage.
  * @param Request $request
  * @return Response
  */
  public function store(Request $request)
  {
    $role = [
      'nama' => 'required',
      'username' => 'required',
      'password' => 'required',
    ];
    $msgs = [
      'nama.required' => 'Nama Lengkap tidak boleh kosong!',
      'username.required' => 'Username tidak boleh kosong!',
      'password.required' => 'Password tidak boleh kosong!'
    ];

    if ($request->nip) {
      $role ['nip'] = 'digits:18|unique:pegawai,nip';
      $msgs['nip.digits'] = 'NIP harus berupa angka berjumlah 18!';
      $msgs['nip.unique'] = 'NIP telah digunakan!';
    }

    Validator::make($request->all(),$role,$msgs)->validate();

    $filepath = null;
    if ($request->hasFile('foto')) {
      $foto = $request->file('foto');
      $allowed_ext = ['jpg','jpeg','png'];
      $peta_ext = $foto->getClientOriginalExtension();

      if ($foto->getSize() > (1024*1000)) {
        return redirect()->back()->withErrors('Ukuran File foto tidak boleh lebih dari 1MB')->withInput();
      }elseif (!in_array(strtolower($peta_ext),$allowed_ext)) {
        return redirect()->back()->withErrors('File foto harus berekstensi jpg, jpeg, atau png')->withInput();
      }

      $filepath = $foto->store('foto_pegawai','public');
    }

    $insert = new Pegawai;
    $insert->uuid = (string) Str::uuid();
    $insert->nama = $request->nama;
    $insert->nip = $request->nip;
    $insert->status_kawin = $request->status_kawin;
    $insert->alamat = $request->alamat;
    $insert->pangkat_golongan = $request->pangkat_golongan;
    $insert->status_kepegawaian = $request->status_kepegawaian;
    $insert->jabatan = $request->jabatan;
    $insert->tempat_lahir = $request->tempat_lahir;
    $insert->tanggal_lahir = $request->tanggal_lahir;
    $insert->jenis_kelamin = $request->jenis_kelamin;
    $insert->agama = $request->agama;
    $insert->unit_kerja = $request->unit_kerja;
    $insert->mulai_masuk = $request->mulai_masuk;
    $insert->golda = $request->golda;
    $insert->tinggi = $request->tinggi;
    $insert->berat = $request->berat;
    $insert->rambut = $request->rambut;
    $insert->bentuk_muka = $request->bentuk_muka;
    $insert->warna_muka = $request->warna_muka;
    $insert->ciri_ciri = $request->ciri_ciri;
    $insert->kegemaran = $request->kegemaran;
    $insert->pendidikan_akhir = $request->pendidikan_akhir;
    $insert->riwayat_pendidikan = json_encode($request->riwayat_pendidikan);
    if ($filepath) {
      $insert->foto = $filepath;
    }

    if ($insert->save()) {
      $insert->user()->insert([
        'uuid'=>Str::uuid(),
        'name'=>$request->nama,
        'username'=>$request->username,
        'password'=>bcrypt($request->password),
        'id_user'=>$insert->id,
        'role'=>'pegawai',
      ]);
      return redirect()->route('pegawai.index')->with('message','Data berhasil disimpan!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  /**
  * Show the specified resource.
  * @param int $uuid
  * @return Response
  */
  public function show($uuid)
  {
    $pegawai = Pegawai::where('uuid',$uuid)->first();
    if (!$pegawai) {
      return redirect()->route('pegawai.index');
    }
    $data = [
      'title'=>'Detail Data Guru & Pegawai',
      'data'=>$pegawai
    ];
    return view('arsip::pegawai.show',$data);
  }

  /**
  * Show the form for editing the specified resource.
  * @param int $uuid
  * @return Response
  */
  public function edit($uuid)
  {
    $pegawai = Pegawai::where('uuid',$uuid)->first();
    if (!$pegawai) {
      return redirect()->route('pegawai.index');
    }
    $data = [
      'title'=>'Ubah Data Guru & Pegawai',
      'data'=>$pegawai
    ];
    return view('arsip::pegawai.edit',$data);
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
      'nama' => 'required',
      'username' => 'required',
    ];
    $msgs = [
      'nama.required' => 'Nama Lengkap tidak boleh kosong!',
      'username.required' => 'Username tidak boleh kosong!'
    ];

    if ($request->nip) {
      $role ['nip'] = 'digits:18|unique:pegawai,nip,'.$uuid.',uuid';
      $msgs['nip.digits'] = 'NIP harus berupa angka berjumlah 18!';
      $msgs['nip.unique'] = 'NIP telah digunakan!';
    }

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = Pegawai::where('uuid',$uuid)->first();

    $filepath = null;
    if ($request->hasFile('foto')) {
      $foto = $request->file('foto');
      $allowed_ext = ['jpg','jpeg','png'];
      $peta_ext = $foto->getClientOriginalExtension();

      if ($foto->getSize() > (1024*1000)) {
        return redirect()->back()->withErrors('Ukuran File foto tidak boleh lebih dari 1MB')->withInput();
      }elseif (!in_array(strtolower($peta_ext),$allowed_ext)) {
        return redirect()->back()->withErrors('File foto harus berekstensi jpg, jpeg, atau png')->withInput();
      }

      Storage::disk('public')->delete($insert->foto);

      $filepath = $foto->store('foto_pegawai','public');
    }

    $insert->uuid = (string) Str::uuid();
    $insert->nama = $request->nama;
    $insert->nip = $request->nip;
    $insert->status_kawin = $request->status_kawin;
    $insert->alamat = $request->alamat;
    $insert->pangkat_golongan = $request->pangkat_golongan;
    $insert->status_kepegawaian = $request->status_kepegawaian;
    $insert->jabatan = $request->jabatan;
    $insert->tempat_lahir = $request->tempat_lahir;
    $insert->tanggal_lahir = $request->tanggal_lahir;
    $insert->jenis_kelamin = $request->jenis_kelamin;
    $insert->agama = $request->agama;
    $insert->unit_kerja = $request->unit_kerja;
    $insert->mulai_masuk = $request->mulai_masuk;
    $insert->golda = $request->golda;
    $insert->tinggi = $request->tinggi;
    $insert->berat = $request->berat;
    $insert->rambut = $request->rambut;
    $insert->bentuk_muka = $request->bentuk_muka;
    $insert->warna_muka = $request->warna_muka;
    $insert->ciri_ciri = $request->ciri_ciri;
    $insert->kegemaran = $request->kegemaran;
    $insert->pendidikan_akhir = $request->pendidikan_akhir;
    $insert->riwayat_pendidikan = json_encode($request->riwayat_pendidikan);
    if ($filepath) {
      $insert->foto = $filepath;
    }

    if ($insert->save()) {
      $login = [
        'name'=>$request->nama,
        'username'=>$request->username,
      ];
      if ($request->password) {
        $login['password'] = bcrypt($request->password);
      }
      $insert->user->update($login);
      return redirect()->route('pegawai.index')->with('message','Data berhasil disimpan!');
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
    $pegawai = Pegawai::where('uuid',$uuid)->first();
    if ($pegawai->foto) {
      Storage::disk('public')->delete($pegawai->foto);
    }
    $pegawai->user->delete();
    if ($pegawai->delete()) {
      return redirect()->route('pegawai.index')->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  public function exportSinglePDF($uuid)
  {
    $pegawai = Pegawai::where('uuid',$uuid)->first();
    $data = [
      'title'=>($pegawai->nip?$pegawai->nip.' - ':'').$pegawai->nama,
      'data'=>$pegawai
    ];
    $view = view('arsip::pegawai.print-single',$data)->render();
    $client = new Client;
    $res = $client->request('POST','http://pdf/pdf',[
      'form_params'=>[
        'html'=>str_replace(url('/'),'http://nginx_arsip/',$view),
        'options[page-width]'=>'21.5cm',
        'options[page-height]'=>'33cm',
      ]
    ]);

    if ($res->getStatusCode() == 200) {
      $filename = $data['title'].'.pdf';
      return response()->attachment($res->getBody()->getContents(),$filename,'application/pdf');
    }
    return redirect()->back()->withErrors(['Tidak dapat mendownload file! Silahkan hubungi operator']);
  }

  public function exportPDF(Request $request)
  {
    $role = '%'.request()->q.'%';
    $rows = request()->rows;

    $pegawai = Pegawai::when(request()->q!='all',function($q) use($role){
      $q->where('nip','like',$role)
      ->orWhere('nama','like',$role)
      ->orWhere('status_kepegawaian','like',$role)
      ->orWhere('jabatan','like',$role)
      ->orWhere('pangkat_golongan','like',$role);
    })
    ->orderBy('id','asc')->paginate($rows, ['*'], 'page');

    $data = [
      'title'=>'Daftar Pegawai UPTD SMPN 39 Sinjai',
      'data'=>$pegawai
    ];
    $view = view('arsip::pegawai.print-all',$data)->render();
    $client = new Client;
    $res = $client->request('POST','http://pdf/pdf',[
      'form_params'=>[
        'html'=>str_replace(url('/'),'http://nginx_arsip/',$view),
        'options[page-width]'=>'21.5cm',
        'options[page-height]'=>'33cm',
        'options[orientation]'=>'Landscape',
      ]
    ]);

    if ($res->getStatusCode() == 200) {
      $filename = $data['title'].'.pdf';
      return response()->attachment($res->getBody()->getContents(),$filename,'application/pdf');
    }
    return redirect()->back()->withErrors(['Tidak dapat mendownload file! Silahkan hubungi operator']);
  }

  public function resetLogin($uuid)
  {
    $pegawai = Pegawai::where('uuid',$uuid)->first();
    $pegawai->user->update([
      'api_token' => null,
      'activate_key' => null,
      'changed_password' => 0,
      'active' => 0,
    ]);
    return redirect()->back()->with('message', 'Data login berhasil direset');
  }
}
