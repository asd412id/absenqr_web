<?php

namespace Modules\Arsip\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Arsip\Entities\Siswa;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;

class SiswaController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    if (request()->ajax()) {
      $data = Siswa::select('uuid','nisn','nis','nama_lengkap','jenis_kelamin','tempat_lahir','tanggal_lahir')->orderBy('id','asc');
      return DataTables::of($data)
      ->addColumn('jk',function($row){
        return $row->jenis_kelamin==1?'Laki - Laki':'Perempuan';
      })
      ->addColumn('ttl',function($row){
        $ttl = $row->tempat_lahir??'-';
        $ttl .= ', ';
        $ttl .= $row->tanggal_lahir?date('d-m-Y',strtotime($row->tanggal_lahir)):'-';
        return $ttl;
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        $btn .= '<a href="'.route('siswa.export.single.pdf',['uuid'=>$row->uuid]).'" class="text-danger" target="_blank" title="Ekspor PDF"><i class="fas fa-file-pdf"></i></a>';

        $btn .= '<a href="'.route('siswa.show',['uuid'=>$row->uuid]).'" class="text-success" title="Detail"><i class="ik ik-info"></i></a>';

        $btn .= ' <a href="'.route('siswa.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

        $btn .= ' <a href="'.route('siswa.destroy',['uuid'=>$row->uuid]).'" class="text-danger hapus" title="Hapus"><i class="ik ik-trash-2"></i></a>';

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
    }

    $data = ['title'=>'Data Siswa'];

    return view('arsip::siswa.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create()
  {
    $data = ['title'=>'Tambah Siswa'];
    return view('arsip::siswa.create',$data);
  }

  /**
  * Store a newly created resource in storage.
  * @param Request $request
  * @return Response
  */
  public function store(Request $request)
  {
    $role = [
      'nis' => 'required|numeric|unique:siswa,nis',
      'nisn' => 'unique:siswa,nisn',
      'nama_lengkap' => 'required',
    ];
    $msgs = [
      'nis.required' => 'NIS tidak boleh kosong!',
      'nis.numeric' => 'NIS harus berupa angka!',
      'nis.unique' => 'NIS telah digunakan!',
      'nisn.unique' => 'NISN telah digunakan!',
      'nama_lengkap.required' => 'Nama Lengkap tidak boleh kosong!',
    ];
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

      $filepath = $foto->store('foto_siswa','public');
    }

    $insert = new Siswa;
    $insert->uuid = (string) Str::uuid();
    $insert->nisn = $request->nisn;
    $insert->nis = $request->nis;
    $insert->kelas = $request->kelas;
    $insert->nama_lengkap = $request->nama_lengkap;
    $insert->nama_panggilan = $request->nama_panggilan;
    $insert->jenis_kelamin = $request->jenis_kelamin;
    $insert->tempat_lahir = $request->tempat_lahir;
    $insert->tanggal_lahir = $request->tanggal_lahir;
    $insert->agama = $request->agama;
    $insert->kewarganegaraan = $request->kewarganegaraan;
    $insert->anak_ke = $request->anak_ke;
    $insert->jumlah_saudara = $request->jumlah_saudara;
    $insert->bahasa_hari = $request->bahasa_hari;
    $insert->alamat = $request->alamat;
    $insert->telp = $request->telp;
    $insert->golda = $request->golda;
    $insert->tinggi = $request->tinggi;
    $insert->berat = $request->berat;
    $insert->kelainan_jasmani = $request->kelainan_jasmani;
    $insert->asal_sekolah = $request->asal_sekolah;
    $insert->tanggal_diterima = $request->tanggal_diterima;
    $insert->nama_ayah = $request->nama_ayah;
    $insert->alamat_ayah = $request->alamat_ayah;
    $insert->telp_ayah = $request->telp_ayah;
    $insert->pekerjaan_ayah = $request->pekerjaan_ayah;
    $insert->nama_ibu = $request->nama_ibu;
    $insert->alamat_ibu = $request->alamat_ibu;
    $insert->telp_ibu = $request->telp_ibu;
    $insert->pekerjaan_ibu = $request->pekerjaan_ibu;
    $insert->nama_wali = $request->nama_wali;
    $insert->alamat_wali = $request->alamat_wali;
    $insert->telp_wali = $request->telp_wali;
    $insert->pekerjaan_wali = $request->pekerjaan_wali;
    $insert->tanggal_tamat = $request->tanggal_tamat;
    $insert->tanggal_ijazah = $request->tanggal_ijazah;
    $insert->nomor_ijazah = $request->nomor_ijazah;
    $insert->pendidikan_lanjut = $request->pendidikan_lanjut;
    if ($filepath) {
      $insert->foto = $filepath;
    }

    if ($insert->save()) {
      return redirect()->route('siswa.index')->with('message','Data berhasil disimpan!');
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
    $siswa = Siswa::where('uuid',$uuid)->first();
    if (!$siswa) {
      return redirect()->route('siswa.index');
    }
    $data = [
      'title'=>'Detail Data Siswa',
      'data'=>$siswa
    ];
    return view('arsip::siswa.show',$data);
  }

  /**
  * Show the form for editing the specified resource.
  * @param int $uuid
  * @return Response
  */
  public function edit($uuid)
  {
    $siswa = Siswa::where('uuid',$uuid)->first();
    if (!$siswa) {
      return redirect()->route('siswa.index');
    }
    $data = [
      'title'=>'Ubah Data Siswa',
      'data'=>$siswa
    ];
    return view('arsip::siswa.edit',$data);
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
      'nis' => 'required|numeric|unique:siswa,nis,'.$uuid.',uuid',
      'nisn' => 'unique:siswa,nisn,'.$uuid.',uuid',
      'nama_lengkap' => 'required',
    ];
    $msgs = [
      'nis.required' => 'NIS tidak boleh kosong!',
      'nis.numeric' => 'NIS harus berupa angka!',
      'nis.unique' => 'NIS telah digunakan!',
      'nisn.unique' => 'NISN telah digunakan!',
      'nama_lengkap.required' => 'Nama Lengkap tidak boleh kosong!',
    ];
    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = Siswa::where('uuid',$uuid)->first();

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

      $filepath = $foto->store('foto_siswa','public');
    }

    $insert->nisn = $request->nisn;
    $insert->nis = $request->nis;
    $insert->kelas = $request->kelas;
    $insert->nama_lengkap = $request->nama_lengkap;
    $insert->nama_panggilan = $request->nama_panggilan;
    $insert->jenis_kelamin = $request->jenis_kelamin;
    $insert->tempat_lahir = $request->tempat_lahir;
    $insert->tanggal_lahir = $request->tanggal_lahir;
    $insert->agama = $request->agama;
    $insert->kewarganegaraan = $request->kewarganegaraan;
    $insert->anak_ke = $request->anak_ke;
    $insert->jumlah_saudara = $request->jumlah_saudara;
    $insert->bahasa_hari = $request->bahasa_hari;
    $insert->alamat = $request->alamat;
    $insert->telp = $request->telp;
    $insert->golda = $request->golda;
    $insert->tinggi = $request->tinggi;
    $insert->berat = $request->berat;
    $insert->kelainan_jasmani = $request->kelainan_jasmani;
    $insert->asal_sekolah = $request->asal_sekolah;
    $insert->tanggal_diterima = $request->tanggal_diterima;
    $insert->nama_ayah = $request->nama_ayah;
    $insert->alamat_ayah = $request->alamat_ayah;
    $insert->telp_ayah = $request->telp_ayah;
    $insert->pekerjaan_ayah = $request->pekerjaan_ayah;
    $insert->nama_ibu = $request->nama_ibu;
    $insert->alamat_ibu = $request->alamat_ibu;
    $insert->telp_ibu = $request->telp_ibu;
    $insert->pekerjaan_ibu = $request->pekerjaan_ibu;
    $insert->nama_wali = $request->nama_wali;
    $insert->alamat_wali = $request->alamat_wali;
    $insert->telp_wali = $request->telp_wali;
    $insert->pekerjaan_wali = $request->pekerjaan_wali;
    $insert->tanggal_tamat = $request->tanggal_tamat;
    $insert->tanggal_ijazah = $request->tanggal_ijazah;
    $insert->nomor_ijazah = $request->nomor_ijazah;
    $insert->pendidikan_lanjut = $request->pendidikan_lanjut;
    if ($filepath) {
      $insert->foto = $filepath;
    }

    if ($insert->save()) {
      return redirect()->route('siswa.index')->with('message','Data berhasil disimpan!');
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
    $siswa = Siswa::where('uuid',$uuid)->first();
    if ($siswa->foto) {
      Storage::disk('public')->delete($siswa->foto);
    }
    if ($siswa->delete()) {
      return redirect()->route('siswa.index')->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }
  public function exportSinglePDF($uuid)
  {
    $siswa = Siswa::where('uuid',$uuid)->first();
    $data = [
      'title'=>$siswa->nis.' - '.$siswa->nama_lengkap,
      'data'=>$siswa
    ];
    $view = view('arsip::siswa.print-single',$data)->render();
    $client = new Client;
    $res = $client->request('POST','http://pdf/pdf',[
      'form_params'=>[
        'html'=>str_replace(url('/'),'http://nginx_arsip/',$view),
        'options[page-width]'=>'21.5cm',
        'options[page-height]'=>'33cm',
      ]
    ]);

    if ($res->getStatusCode() == 200) {
      $file_temp = storage_path('temp/'.$siswa->uuid.'_'.uniqid().'.pdf');

      file_put_contents($file_temp,$res->getBody()->getContents());

      return response()->file($file_temp,[
        'Content-Disposition'=>'filename="'.$data['title'].'.pdf"'
      ])->deleteFileAfterSend(true);
    }
    return redirect()->back()->withErrors(['Tidak dapat mendownload file! Silahkan hubungi operator']);
  }

  public function exportPDF(Request $request)
  {
    $role = '%'.request()->q.'%';
    $rows = request()->rows;

    $siswa = Siswa::when(request()->q!='all',function($q) use($role){
      $q->where('nis','like',$role)
      ->orWhere('nisn','like',$role)
      ->orWhere('nama_lengkap','like',$role)
      ->orWhere('tempat_lahir','like',$role)
      ->orWhere('tanggal_lahir','like',$role);
    })
    ->orderBy('id','asc')->paginate($rows, ['*'], 'page');

    $data = [
      'title'=>'Daftar Siswa UPTD SMPN 39 Sinjai',
      'data'=>$siswa
    ];
    $view = view('arsip::siswa.print-all',$data)->render();
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
      $file_temp = storage_path('temp/daftar-siswa'.uniqid().'.pdf');

      file_put_contents($file_temp,$res->getBody()->getContents());

      return response()->file($file_temp,[
        'Content-Disposition'=>'filename="'.$data['title'].'.pdf"'
      ])->deleteFileAfterSend(true);
    }
    return redirect()->back()->withErrors(['Tidak dapat mendownload file! Silahkan hubungi operator']);
  }
}
