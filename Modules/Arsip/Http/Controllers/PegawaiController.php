<?php

namespace Modules\Arsip\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Arsip\Entities\Pegawai;
use App\User;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;
use Carbon\Carbon;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PegawaiController extends Controller
{
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index()
  {
    if (request()->ajax()) {
      $data = User::orderBy('urutan', 'asc')
        ->where('role', 'pegawai')
        ->orderBy('name', 'asc')
        ->with('pegawai')
        ->whereHas('pegawai');
      return DataTables::of($data)
        ->addColumn('jk', function ($row) {
          return $row->pegawai->jenis_kelamin == 1 ? 'Laki - Laki' : 'Perempuan';
        })
        ->addColumn('skep', function ($row) {
          return strtoupper($row->pegawai->status_kepegawaian);
        })
        ->addColumn('activate_key', function ($row) {
          $data = $row->activate_key ?? '-';
          return $data;
        })
        ->addColumn('action', function ($row) {

          $btn = '<div class="table-actions">';

          $btn .= '<a href="' . route('pegawai.export.single.pdf', ['uuid' => $row->pegawai->uuid]) . '" class="text-danger" target="_blank" title="Ekspor PDF"><i class="fas fa-file-pdf"></i></a>';

          $btn .= '<a href="' . route('pegawai.show', ['uuid' => $row->pegawai->uuid]) . '" class="text-success" title="Detail"><i class="ik ik-info"></i></a>';

          if (\Auth::user()->role == 'admin') {
            $btn .= ' <a href="' . route('pegawai.reset.login', ['uuid' => $row->pegawai->uuid]) . '" class="text-warning confirm" data-text="Reset login ' . $row->pegawai->nama . '?" title="Reset Login"><i class="ik ik-refresh-cw"></i></a>';

            $btn .= ' <a href="' . route('pegawai.edit', ['uuid' => $row->pegawai->uuid]) . '" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

            $btn .= ' <a href="' . route('pegawai.destroy', ['uuid' => $row->pegawai->uuid]) . '" class="text-danger confirm" data-text="Hapus data ' . $row->pegawai->nama . '?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
          }

          $btn .= '</div>';

          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    $data = ['title' => 'Data Guru & Pegawai'];

    return view('arsip::pegawai.index', $data);
  }

  /**
   * Show the form for creating a new resource.
   * @return Response
   */
  public function create()
  {
    $data = ['title' => 'Tambah Pegawai'];
    return view('arsip::pegawai.create', $data);
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
      'username' => 'required|unique:users,username',
      'password' => 'required',
    ];
    $msgs = [
      'nama.required' => 'Nama Lengkap tidak boleh kosong!',
      'username.required' => 'Username tidak boleh kosong!',
      'username.unique' => 'Username telah digunakan!',
      'password.required' => 'Password tidak boleh kosong!'
    ];

    if ($request->nip) {
      $role['nip'] = 'digits:18|unique:pegawai,nip';
      $msgs['nip.digits'] = 'NIP harus berupa angka berjumlah 18!';
      $msgs['nip.unique'] = 'NIP telah digunakan!';
    }

    if ($request->nuptk) {
      $role['nuptk'] = 'digits:16|unique:pegawai,nuptk';
      $msgs['nuptk.digits'] = 'NUPTK harus berupa angka berjumlah 18!';
      $msgs['nuptk.unique'] = 'NUPTK telah digunakan!';
    }

    if ($request->urutan) {
      $role['urutan'] = 'numeric';
      $msgs['urutan.numeric'] = 'Urutan harus berupa angka!';
    }

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

      $filepath = $foto->store('foto_pegawai', 'public');
    }

    $insert = new Pegawai;
    $insert->uuid = (string) Str::uuid();
    $insert->nama = $request->nama;
    $insert->nip = $request->nip;
    $insert->nuptk = $request->nuptk;
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
        'uuid' => Str::uuid(),
        'name' => $request->nama,
        'username' => $request->username,
        'password' => bcrypt($request->password),
        'id_user' => $insert->id,
        'role' => 'pegawai',
        'urutan' => $request->urutan ?? 9999,
      ]);
      return redirect()->route('pegawai.index')->with('message', 'Data berhasil disimpan!');
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
    $pegawai = Pegawai::where('uuid', $uuid)->first();
    if (!$pegawai) {
      return redirect()->route('pegawai.index');
    }
    $data = [
      'title' => 'Detail Data Guru & Pegawai',
      'data' => $pegawai
    ];
    return view('arsip::pegawai.show', $data);
  }

  /**
   * Show the form for editing the specified resource.
   * @param int $uuid
   * @return Response
   */
  public function edit($uuid)
  {
    $pegawai = Pegawai::where('uuid', $uuid)->first();
    if (!$pegawai) {
      return redirect()->route('pegawai.index');
    }
    $data = [
      'title' => 'Ubah Data Guru & Pegawai',
      'data' => $pegawai
    ];
    return view('arsip::pegawai.edit', $data);
  }

  /**
   * Update the specified resource in storage.
   * @param Request $request
   * @param int $uuid
   * @return Response
   */
  public function update(Request $request, $uuid)
  {
    $insert = Pegawai::where('uuid', $uuid)->first();
    $role = [
      'nama' => 'required',
      'username' => 'required|unique:users,username,' . $insert->user->uuid . ',uuid',
    ];
    $msgs = [
      'nama.required' => 'Nama Lengkap tidak boleh kosong!',
      'username.required' => 'Username tidak boleh kosong!',
      'username.unique' => 'Username telah digunakan!'
    ];

    if ($request->nip) {
      $role['nip'] = 'digits:18|unique:pegawai,nip,' . $uuid . ',uuid';
      $msgs['nip.digits'] = 'NIP harus berupa angka berjumlah 18!';
      $msgs['nip.unique'] = 'NIP telah digunakan!';
    }

    if ($request->nuptk) {
      $role['nuptk'] = 'digits:16|unique:pegawai,nuptk,' . $uuid . ',uuid';
      $msgs['nuptk.digits'] = 'NUPTK harus berupa angka berjumlah 18!';
      $msgs['nuptk.unique'] = 'NUPTK telah digunakan!';
    }

    if ($request->urutan) {
      $role['urutan'] = 'numeric';
      $msgs['urutan.numeric'] = 'Urutan harus berupa angka!';
    }

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

      Storage::disk('public')->delete($insert->foto);

      $filepath = $foto->store('foto_pegawai', 'public');
    }

    $insert->uuid = (string) Str::uuid();
    $insert->nama = $request->nama;
    $insert->nip = $request->nip;
    $insert->nuptk = $request->nuptk;
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
        'name' => $request->nama,
        'username' => $request->username,
        'urutan' => $request->urutan ?? 9999,
      ];
      if ($request->password) {
        $login['password'] = bcrypt($request->password);
      }
      $insert->user->update($login);
      return redirect()->route('pegawai.index')->with('message', 'Data berhasil disimpan!');
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
    $pegawai = Pegawai::where('uuid', $uuid)->first();
    if ($pegawai->foto) {
      Storage::disk('public')->delete($pegawai->foto);
    }
    $pegawai->user->absenRuang()->detach();
    $pegawai->user->jadwal()->detach();
    $pegawai->user->absenDesc()->delete();
    $pegawai->user->delete();
    if ($pegawai->delete()) {
      return redirect()->route('pegawai.index')->with('message', 'Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  public function exportSinglePDF($uuid)
  {
    $pegawai = Pegawai::where('uuid', $uuid)->first();
    $data = [
      'title' => ($pegawai->nuptk ? $pegawai->nuptk . ' - ' : '') . $pegawai->nama,
      'data' => $pegawai
    ];
    $params = [
      'format' => [215, 330]
    ];
    $filename = $data['title'] . '.pdf';

    $pdf = PDF::loadView('arsip::pegawai.print-single', $data, [], $params);
    return $pdf->stream($filename);
  }

  public function exportPDF(Request $request)
  {
    $role = '%' . request()->q . '%';
    $rows = request()->rows;

    $pegawai = User::orderBy('urutan', 'asc')
      ->where('role', 'pegawai')
      ->orderBy('name', 'asc')
      ->whereHas('pegawai', function ($q) use ($role) {
        $q->when(request()->q != 'all', function ($q) use ($role) {
          $q->where('nip', 'like', $role)
            ->orWhere('nama', 'like', $role)
            ->orWhere('status_kepegawaian', 'like', $role)
            ->orWhere('jabatan', 'like', $role)
            ->orWhere('pangkat_golongan', 'like', $role);
        });
      })->with('pegawai')
      ->paginate($rows, ['*'], 'page');

    $data = [
      'title' => 'Daftar Pegawai UPTD SMPN 39 Sinjai',
      'data' => $pegawai
    ];
    $params = [
      'format' => [215, 330]
    ];
    $params['orientation'] = 'L';
    $filename = $data['title'] . '.pdf';

    $pdf = PDF::loadView('arsip::pegawai.print-all', $data, [], $params);
    return $pdf->stream($filename);
  }

  public function resetLogin($uuid)
  {
    $pegawai = Pegawai::where('uuid', $uuid)->first();
    $pegawai->user->update([
      'api_token' => null,
      'activate_key' => null,
      'changed_password' => 0,
      'active' => 0,
    ]);
    return redirect()->back()->with('message', 'Data login berhasil direset');
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
        $arr = $spreadsheet->getSheet(0)->toArray();

        if ($arr[0][1] != 'NAMA' && $arr[0][5] != 'USERNAME') {
          return redirect()->back()->withErrors('File yang diupload tidak sesuai format!');
        }

        if ($request->status == 'new') {
          $pegawais = Pegawai::all();
          foreach ($pegawais as $p) {
            $p->user->jadwal()->detach();
            $p->user()->delete();
            $p->delete();
          }
          User::where('role', 'pegawai')->delete();
          $fotos = Storage::disk('public')->allFiles('foto_pegawai');
          Storage::disk('public')->delete($fotos);
        }

        foreach ($arr as $krow => $row) {
          if ($krow > 0) {
            if ($row[1] == '' || $row[5] == '') {
              continue;
            }

            $new = false;
            $user = User::where('username', $row[5])->first();
            $import = $user ? $user->pegawai : null;

            if (!$import) {
              $import = new Pegawai();
              $import->uuid = (string) Str::uuid();
              $new = true;
            }

            $import->nip = $row[3];
            $import->nama = $row[1];
            $import->jenis_kelamin = $row[2] == 'L' ? 1 : 2;
            $import->nuptk = $row[4];
            $import->jabatan = $row[7];
            $import->status_kepegawaian = $row[9];
            $import->riwayat_pendidikan = json_encode([]);

            $import->save();

            if ($new) {
              $import->user()->insert([
                'uuid' => Str::uuid(),
                'name' => $import->nama,
                'username' => $row[5],
                'password' => bcrypt($row[6]),
                'id_user' => $import->id,
                'role' => 'pegawai',
                'urutan' => $row[8],
              ]);
            } else {
              $import->user->update([
                'name' => $import->nama,
                'username' => $row[5],
                'password' => bcrypt($row[6]),
                'urutan' => $row[8],
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
    return response()->download(public_path('assets/files/template_excel_pegawai.xlsx'), strtoupper("Template Data Pegawai") . ".xlsx");
  }
}
