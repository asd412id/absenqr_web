<?php

namespace Modules\Arsip\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Arsip\Entities\Siswa;
use App\User;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;
use IOFactory;
use Carbon\Carbon;
use PDF;

class SiswaController extends Controller
{
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index()
  {
    if (request()->ajax()) {
      $data = Siswa::orderBy('nama_lengkap', 'asc');
      return DataTables::of($data)
        ->addColumn('jk', function ($row) {
          return $row->jenis_kelamin == 1 ? 'Laki - Laki' : 'Perempuan';
        })
        ->addColumn('ttl', function ($row) {
          $ttl = $row->tempat_lahir ?? '-';
          $ttl .= ', ';
          $ttl .= $row->tanggal_lahir;
          return $ttl;
        })
        ->addColumn('activate_key', function ($row) {
          $data = $row->user->activate_key ?? '-';
          return $data;
        })
        ->addColumn('action', function ($row) {

          $btn = '<div class="table-actions">';

          $btn .= '<a href="' . route('siswa.export.single.pdf', ['uuid' => $row->uuid]) . '" class="text-danger" target="_blank" title="Ekspor PDF"><i class="fas fa-file-pdf"></i></a>';

          $btn .= '<a href="' . route('siswa.show', ['uuid' => $row->uuid]) . '" class="text-success" title="Detail"><i class="ik ik-info"></i></a>';

          if (\Auth::user()->role == 'admin') {
            $btn .= ' <a href="' . route('siswa.reset.login', ['uuid' => $row->uuid]) . '" class="text-warning confirm" data-text="Reset login ' . $row->nama_lengkap . '?" title="Reset Login"><i class="ik ik-refresh-cw"></i></a>';

            $btn .= ' <a href="' . route('siswa.edit', ['uuid' => $row->uuid]) . '" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

            $btn .= ' <a href="' . route('siswa.destroy', ['uuid' => $row->uuid]) . '" class="text-danger confirm" data-text="Hapus data ' . $row->nama_lengkap . '?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
          }

          $btn .= '</div>';

          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    $data = ['title' => 'Data Siswa'];

    return view('arsip::siswa.index', $data);
  }

  /**
   * Show the form for creating a new resource.
   * @return Response
   */
  public function create()
  {
    $data = ['title' => 'Tambah Siswa'];
    return view('arsip::siswa.create', $data);
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
    Validator::make($request->all(), $role, $msgs)->validate();

    $filepath = null;
    if ($request->hasFile('foto')) {
      $foto = $request->file('foto');
      $allowed_ext = ['jpg', 'jpeg', 'png'];
      $peta_ext = $foto->getClientOriginalExtension();

      if ($foto->getSize() > (1024 * 1000)) {
        return redirect()->back()->withErrors('Ukuran File foto tidak boleh lebih dari 1MB')->withInput();
      } elseif (!in_array(strtolower($peta_ext), $allowed_ext)) {
        return redirect()->back()->withErrors('File foto harus berekstensi jpg, jpeg, atau png')->withInput();
      }

      $filepath = $foto->store('foto_siswa', 'public');
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
      $insert->user()->insert([
        'uuid' => Str::uuid(),
        'name' => $insert->nama_lengkap,
        'username' => $insert->nis,
        'password' => bcrypt($insert->nis),
        'id_user' => $insert->id,
        'role' => 'siswa',
      ]);
      return redirect()->route('siswa.index')->with('message', 'Data berhasil disimpan!');
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
    $siswa = Siswa::where('uuid', $uuid)->first();
    if (!$siswa) {
      return redirect()->route('siswa.index');
    }
    $data = [
      'title' => 'Detail Data Siswa',
      'data' => $siswa
    ];
    return view('arsip::siswa.show', $data);
  }

  /**
   * Show the form for editing the specified resource.
   * @param int $uuid
   * @return Response
   */
  public function edit($uuid)
  {
    $siswa = Siswa::where('uuid', $uuid)->first();
    if (!$siswa) {
      return redirect()->route('siswa.index');
    }
    $data = [
      'title' => 'Ubah Data Siswa',
      'data' => $siswa
    ];
    return view('arsip::siswa.edit', $data);
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
      'nis' => 'required|numeric|unique:siswa,nis,' . $uuid . ',uuid',
      'nisn' => 'unique:siswa,nisn,' . $uuid . ',uuid',
      'nama_lengkap' => 'required',
    ];
    $msgs = [
      'nis.required' => 'NIS tidak boleh kosong!',
      'nis.numeric' => 'NIS harus berupa angka!',
      'nis.unique' => 'NIS telah digunakan!',
      'nisn.unique' => 'NISN telah digunakan!',
      'nama_lengkap.required' => 'Nama Lengkap tidak boleh kosong!',
    ];
    Validator::make($request->all(), $role, $msgs)->validate();

    $insert = Siswa::where('uuid', $uuid)->first();

    $filepath = null;
    if ($request->hasFile('foto')) {
      $foto = $request->file('foto');
      $allowed_ext = ['jpg', 'jpeg', 'png'];
      $peta_ext = $foto->getClientOriginalExtension();

      if ($foto->getSize() > (1024 * 1000)) {
        return redirect()->back()->withErrors('Ukuran File foto tidak boleh lebih dari 1MB')->withInput();
      } elseif (!in_array(strtolower($peta_ext), $allowed_ext)) {
        return redirect()->back()->withErrors('File foto harus berekstensi jpg, jpeg, atau png')->withInput();
      }

      Storage::disk('public')->delete($insert->foto);

      $filepath = $foto->store('foto_siswa', 'public');
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
      $insert->user->update([
        'name' => $insert->nama_lengkap
      ]);
      return redirect()->route('siswa.index')->with('message', 'Data berhasil disimpan!');
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
    $siswa = Siswa::where('uuid', $uuid)->first();
    if ($siswa->foto) {
      Storage::disk('public')->delete($siswa->foto);
    }
    $siswa->user->absenRuang()->detach();
    $siswa->user->jadwal()->detach();
    $siswa->user->absenDesc()->delete();
    $siswa->user->delete();
    if ($siswa->delete()) {
      return redirect()->route('siswa.index')->with('message', 'Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }
  public function exportSinglePDF($uuid)
  {
    $siswa = Siswa::where('uuid', $uuid)->first();
    $data = [
      'title' => $siswa->nis . ' - ' . $siswa->nama_lengkap,
      'data' => $siswa
    ];
    $params = [
      'format' => [215, 330]
    ];
    $filename = $data['title'] . '.pdf';

    $pdf = PDF::loadView('arsip::siswa.print-single', $data, [], $params);
    return $pdf->stream($filename);
  }

  public function exportPDF(Request $request)
  {
    $role = '%' . request()->q . '%';
    $rows = request()->rows;

    $siswa = Siswa::when(request()->q != 'all', function ($q) use ($role) {
      $q->where('nisn', 'like', $role)
        ->orWhere('nis', 'like', $role)
        ->orWhere('nama_lengkap', 'like', $role)
        ->orWhere('tempat_lahir', 'like', $role)
        ->orWhere('tanggal_lahir', 'like', $role)
        ->orWhere('asal_sekolah', 'like', $role);
    })
      ->orderBy('nama_lengkap', 'asc')->paginate($rows, ['*'], 'page');

    $data = [
      'title' => 'Daftar Siswa UPTD SMPN 39 Sinjai',
      'data' => $siswa
    ];
    $params = [
      'format' => [215, 330],
      'orientation' => 'L',
    ];
    $filename = $data['title'] . '.pdf';

    $pdf = PDF::loadView('arsip::siswa.print-all', $data, [], $params);
    return $pdf->stream($filename);
  }

  public function importExcel(Request $request)
  {
    if ($request->file_excel == null) {
      return redirect()->back()->withErrors('File harus diupload!');
    }
    if ($request->file('file_excel')->isValid()) {
      $ext = ['xlsx', 'xls', 'bin', 'ods'];
      if (in_array($request->file_excel->getClientOriginalExtension(), $ext)) {
        $spreadsheet = IOFactory::load($request->file_excel->path());
        $arr = $spreadsheet->getSheetByName('Data Siswa')->toArray();

        if ($arr[0][1] != 'NIS') {
          return redirect()->back()->withErrors('File yang diupload tidak sesuai format!');
        }

        if ($request->status == 'new') {
          Siswa::truncate();
          User::where('role', 'siswa')->delete();
          $fotos = Storage::disk('public')->allFiles('foto_siswa');
          Storage::disk('public')->delete($fotos);
        }

        foreach ($arr as $krow => $row) {
          if ($krow > 1) {
            if ($row[1] == '') {
              continue;
            }

            $new = false;
            $import = Siswa::where('nis', $row[1])->first();

            if (!$import) {
              $import = new Siswa;
              $import->uuid = (string) Str::uuid();
              $new = true;
            }

            $import->nis = $row[1];
            $import->nama_lengkap = $row[2];
            $import->jenis_kelamin = $row[3] == 'L' ? 1 : 2;
            $import->tempat_lahir = $row[4];
            $import->tanggal_lahir = $row[5] ? Carbon::createFromFormat('Y/m/d', $row[5])->format('d-m-Y') : null;
            $import->asal_sekolah = $row[6];
            $import->alamat = $row[7];
            $import->nama_ayah = $row[11];
            $import->pekerjaan_ayah = $row[9];
            $import->nama_ibu = $row[12];
            $import->pekerjaan_ibu = $row[10];

            $import->save();

            if ($new) {
              $import->user()->insert([
                'uuid' => Str::uuid(),
                'name' => $import->nama_lengkap,
                'username' => $import->nis,
                'password' => bcrypt($import->nis),
                'id_user' => $import->id,
                'role' => 'siswa',
              ]);
            } else {
              $import->user->update([
                'name' => $import->nama_lengkap,
              ]);
            }
          }
        }

        if ($import) {
          return redirect()->back()->with('message', 'Data berhasil diimpor.');
        } else {
          return redirect()->back()->with('message', 'Kesalahan saat mengimpor data atau format file tidak benar!');
        }
      }
    }
    return redirect()->back()->withErrors('File yang diupload tidak sesuai format!');
  }

  public function downloadTemplateExcel()
  {
    return response()->download(public_path('assets/files/template_excel_siswa.xlsx'), strtoupper("Template Data Siswa") . ".xlsx");
  }

  public function resetLogin($uuid)
  {
    $siswa = Siswa::where('uuid', $uuid)->first();
    $siswa->user->update([
      'username' => $siswa->nis,
      'password' => bcrypt($siswa->nis),
      'api_token' => null,
      'activate_key' => null,
      'changed_password' => 0,
      'active' => 0,
    ]);
    return redirect()->back()->with('message', 'Data login berhasil direset');
  }
}
