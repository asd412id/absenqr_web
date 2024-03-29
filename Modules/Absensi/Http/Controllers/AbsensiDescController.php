<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\AbsensiDesc;
use Modules\Absensi\Entities\Jadwal;
use App\User;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;
use Carbon\Carbon;

class AbsensiDescController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    if (request()->ajax()) {
      $data = AbsensiDesc::with('user')
      ->orderBy('time','desc')
      ->orderBy('updated_at','desc');
      return DataTables::of($data)
      ->addColumn('get_time',function($row){
        return $row->time->locale('id')->translatedFormat('d F Y').($row->time_end?' - '.$row->time_end->locale('id')->translatedFormat('d F Y'):'');
      })
      ->addColumn('get_desc',function($row){
        return nl2br($row->desc);
      })
      ->addColumn('get_jadwal',function($row){
        $jadwal = [];
        $getJadwal = Jadwal::whereIn('id',$row->jadwal??[])->get();
        if ($getJadwal) {
          foreach ($getJadwal as $key => $jd) {
            array_push($jadwal,'<em class="badge badge-primary">'.$jd->nama_jadwal.($jd->alias?' ('.$jd->alias.')':'').' - '.$jd->get_ruang->nama_ruang.'</em>');
          }
        }
        return is_array($jadwal)&&count($jadwal)?implode(" ",$jadwal):'-';
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('absensi.desc.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('absensi.desc.destroy',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus keterangan '.$row->user->name.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action','get_desc','get_jadwal'])
      ->make(true);
    }

    $data = ['title'=>'Keterangan Absensi'];

    return view('absensi::desc.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create(Request $r)
  {
    if (request()->ajax()) {
      $start = Carbon::createFromFormat('Y/m/d',$r->start);
      $end = Carbon::createFromFormat('Y/m/d',$r->end);
      $periods = $start->toPeriod($end);

      $jadwal = Jadwal::whereHas('user',function($q) use($r){
        $q->where('id',$r->user);
      })
      ->where(function($q) use($periods){
        foreach ($periods as $key => $v) {
          $q->orWhere('hari','like','%"'.$v->format('N').'"%');
        }
      })
      ->with('get_ruang')
      ->orderBY('nama_jadwal','asc')
      ->get();
      return response()->json($jadwal);
    }
    $data = [
      'title'=>'Tambah Keterangan Absensi',
    ];
    return view('absensi::desc.create',$data);
  }

  /**
  * Store a newly created resource in storage.
  * @param Request $request
  * @return Response
  */
  public function store(Request $request)
  {
    $role = [
      'user' => 'required',
      'time' => 'required',
      'time_end' => 'required',
      'desc' => 'required',
      'jadwal' => 'required',
    ];
    $msgs = [
      'user.required' => 'User tidak boleh kosong!',
      'time.required' => 'Waktu mulai tidak boleh kosong!',
      'time_end.required' => 'Waktu selesai tidak boleh kosong!',
      'desc.required' => 'Keterangan tidak boleh kosong!',
      'jadwal.required' => 'Jadwal harus dipilih!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = AbsensiDesc::where('user_id',$request->user)
    ->where('time',$request->time)
    ->where('time_end',$request->time_end)
    ->where('jadwal',json_encode($request->jadwal))
    ->first();

    if (!$insert) {
      $insert = new AbsensiDesc;
      $insert->uuid = (string) Str::uuid();
    }

    $insert->user_id = $request->user;
    $insert->time = $request->time;
    $insert->time_end = $request->time_end;
    $insert->desc = $request->desc;
    $insert->jadwal = $request->jadwal;

    if ($insert->save()) {
      return redirect()->route('absensi.desc.index')->with('message','Data berhasil disimpan!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  /**
  * Show the form for editing the specified resource.
  * @param int $uuid
  * @return Response
  */
  public function edit($uuid,Request $r)
  {
    $desc = AbsensiDesc::where('uuid',$uuid)->first();
    if (!$desc) {
      return redirect()->route('absensi.desc.index');
    }
    if (request()->ajax()) {
      $start = Carbon::createFromFormat('Y/m/d',$r->start);
      $end = Carbon::createFromFormat('Y/m/d',$r->end);
      $periods = $start->toPeriod($end);

      $jadwal = Jadwal::whereHas('user',function($q) use($r){
        $q->where('id',$r->user);
      })
      ->where(function($q) use($periods){
        foreach ($periods as $key => $v) {
          $q->orWhere('hari','like','%"'.$v->format('N').'"%');
        }
      })
      ->with('get_ruang')
      ->orderBY('nama_jadwal','asc')
      ->get();
      return response()->json($jadwal);
    }
    $data = [
      'title'=>'Ubah Keterangan Absensi',
      'user'=>User::where('role','!=','admin')->get(),
      'data'=>$desc
    ];
    return view('absensi::desc.edit',$data);
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
      'time' => 'required',
      'desc' => 'required',
      'jadwal' => 'required',
    ];
    $msgs = [
      'time.required' => 'Waktu tidak boleh kosong!',
      'desc.required' => 'Keterangan tidak boleh kosong!',
      'jadwal.required' => 'Jadwal tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = AbsensiDesc::where('uuid',$uuid)->first();
    $insert->time = $request->time;
    $insert->time_end = $request->time_end;
    $insert->desc = $request->desc;
    $insert->jadwal = $request->jadwal;

    if ($insert->save()) {
      return redirect()->route('absensi.desc.index')->with('message','Data berhasil disimpan!');
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
    $desc = AbsensiDesc::where('uuid',$uuid)->first();
    if ($desc->delete()) {
      return redirect()->route('absensi.desc.index')->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }
}
