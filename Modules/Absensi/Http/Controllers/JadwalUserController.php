<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\User;
use Carbon\Carbon;
use Modules\Absensi\Entities\Jadwal;

use DataTables;
use Modules\Absensi\Entities\Ruang;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Str;

class JadwalUserController extends Controller
{
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index()
  {
    if (request()->ajax()) {
      $utype = request()->user ?? 'pegawai';
      $data = User::with('jadwal')->where('role', '!=', 'admin')->where('role', $utype)->orderBy('name', 'asc');
      return DataTables::of($data)
        ->addColumn('jadwal', function ($row) {
          $jd = [];
          if (count($row->jadwal)) {
            foreach ($row->jadwal as $key => $j) {
              array_push($jd, '<span class="badge badge-primary">' . $j->nama_jadwal . ($j->alias ? ' (' . $j->alias . ')' : '') . ' - ' . $j->get_ruang->nama_ruang . '</span>');
            }
          }
          return count($jd) ? implode(" ", $jd) : '-';
        })
        ->addColumn('role_text', function ($row) {
          return ucwords($row->role);
        })
        ->addColumn('action', function ($row) {

          $btn = '<div class="table-actions">';

          if (\Auth::user()->role == 'admin') {
            $btn .= ' <a href="' . route('absensi.jadwal.user.edit', ['uuid' => $row->uuid]) . '" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

            $btn .= ' <a href="' . route('absensi.jadwal.user.reset', ['uuid' => $row->uuid]) . '" class="text-danger confirm" data-text="Reset Jadwal ' . $row->name . '?" title="Reset Jadwal"><i class="ik ik-refresh-cw"></i></a>';
          }

          $btn .= '</div>';

          return $btn;
        })
        ->rawColumns(['action', 'jadwal'])
        ->make(true);
    }

    $data = ['title' => 'Jadwal Absen User'];

    return view('absensi::jadwal-user.index', $data);
  }

  public function edit($uuid)
  {
    $user = User::where('uuid', $uuid)->first();
    $data = [
      'title' => 'Ubah Jadwal Absen',
      'data' => $user,
      'jadwal' => Jadwal::all(),
    ];

    return view('absensi::jadwal-user.edit', $data);
  }

  public function update($uuid, Request $request)
  {
    Jadwal::whereIn('id', $request->jadwal_user)
      ->update(['to_user' => 4]);

    $user = User::where('uuid', $uuid)->first();
    $user->jadwal()->sync($request->jadwal_user);
    return redirect()->route('absensi.jadwal.user.index')->with('message', 'Data berhasil disimpan!');
  }

  public function reset($uuid)
  {
    $user = User::where('uuid', $uuid)->first();
    $user->jadwal()->detach();
    return redirect()->route('absensi.jadwal.user.index')->with('message', 'Data berhasil direset!');
  }

  public function downloadTemplateExcel($uuid)
  {
    $user = User::where('uuid', $uuid)->first();
    $fileName = strtoupper("Jadwal $user->name") . ".xlsx";
    return response()->download(public_path('assets/files/jadwal.xlsx'), $fileName);
  }

  public function importExcel($uuid, Request $request)
  {
    if ($request->file_excel == null) {
      return redirect()->back()->withErrors('File harus diupload!');
    }
    if ($request->file('file_excel')->isValid()) {
      $ext = ['xlsx', 'xls', 'bin', 'ods'];
      $haris = [
        "senin" => 1,
        "selasa" => 2,
        "rabu" => 3,
        "kamis" => 4,
        "jum'at" => 5,
        "sabtu" => 6,
        "ahad" => 7,
      ];
      if (in_array($request->file_excel->getClientOriginalExtension(), $ext)) {
        $spreadsheet = IOFactory::load($request->file_excel->path());
        $sheet = $spreadsheet->getSheetByName('Jadwal')->toArray();

        $user = User::where('uuid', $uuid)->first();

        $jids = [];

        foreach ($sheet as $row => $col) {
          if ($row == 0) {
            continue;
          }

          $ruang = Ruang::where('nama_ruang', $col[1])->first();
          if (!$ruang) {
            continue;
          }

          $hari = $haris[strtolower($col[0])];
          $import = Jadwal::where('ruang', $ruang->id)
            ->where('nama_jadwal', $col[2])
            ->where('alias', $col[3])
            ->where('cin', $col[4])
            ->where('cout', $col[5])
            ->where('to_user', 4)
            ->first();

          if (!$import) {
            $import = new Jadwal();
            $import->uuid = Str::uuid();
          }

          $cin = Carbon::createFromFormat("H:i", $col[4]);
          $cout = Carbon::createFromFormat("H:i", $col[5]);

          $_hari = $import->hari ?? [];
          array_push($_hari, (string)$hari);
          $_hari = array_unique($_hari);

          $import->hari = $_hari;
          $import->ruang = $ruang->id;
          $import->nama_jadwal = $col[2];
          $import->alias = $col[3];
          $import->cin = $col[4];
          $import->start_cin = $cin->copy()->subMinutes(30)->format("H:i");
          $import->end_cin = $cin->copy()->addMinutes(90)->format("H:i");
          $import->cout = $col[5];
          $import->start_cout = $cout->copy()->subMinutes(30)->format("H:i");
          $import->end_cout = $cout->copy()->addMinutes(90)->format("H:i");
          $import->early = intval($col[6]) ?? 0;
          $import->late = intval($col[7]) ?? 0;
          $import->menit_per_jam = intval($col[8]) ?? 60;
          $import->satuan_jam = $col[9] ?? 'Jam';
          $import->to_user = 4;
          $import->save();

          array_push($jids, $import->id);
        }

        $jids = array_unique($jids);

        $user->jadwal()->sync($jids);


        if ($jids) {
          return redirect()->route('absensi.jadwal.user.index', ['uuid' => $uuid])->with('message', 'Data berhasil diimpor.');
        } else {
          return redirect()->back()->with('message', 'Kesalahan saat mengimpor data atau format file tidak benar!');
        }
      }
    }
    return redirect()->back()->withErrors('File yang diupload tidak sesuai format!');
  }
}
