<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\User;
use Modules\Absensi\Entities\Jadwal;
use Modules\Payroll\Entities\Payroll;
use DataTables;
use Validator;
use Str;

class PayrollUserController extends Controller
{
  public function __construct()
  {
    $this->title = "Gaji Pegawai";
  }

  public function index()
  {
    if (request()->ajax()) {
      $data = User::select('id','uuid','name')
      ->whereHas('payroll')
      ->with('payroll')
      ->orderBy('name','asc');
      return DataTables::of($data)
      ->addColumn('beban_kerja',function($row){
        return $row->payroll->count();
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('payroll.user.show',['uuid'=>$row->uuid]).'" class="text-primary" title="Detail"><i class="ik ik-info"></i></a>';

          $btn .= ' <a href="'.route('payroll.user.reset',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus data gaji '.$row->name.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action','desc'])
      ->make(true);
    }

    $data = [
      'title' => $this->title
    ];
    return view('payroll::user.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create($uuid=null)
  {
    if (request()->ajax()) {
      $jadwal = Jadwal::whereHas('user',function($q){
        $q->where('id',request()->user);
      })
      ->with('get_ruang')
      ->get();
      return response()->json($jadwal);
    }

    $user = User::where('uuid',$uuid)->first();

    $data = [
      'title' => $this->title,
      'subtitle' => 'Tambah Pegawai Baru',
      'user' => $user
    ];
    return view('payroll::user.create',$data);
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
      'name' => 'required',
      'gaji' => 'required',
      'menit' => 'required',
      'jadwal' => 'required'
    ];
    $msgs = [
      'user.required' => 'User tidak boleh kosong!',
      'name.required' => 'Nama gaji tidak boleh kosong!',
      'gaji.required' => 'Jumlah gaji tidak boleh kosong!',
      'menit.required' => 'Menit tidak boleh kosong!',
      'jadwal.required' => 'Jadwal harus dipilih!'
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $user = User::find($request->user);

    $insert = new Payroll;
    $insert->uuid = (string) Str::uuid();
    $insert->user_id = $request->user;
    $insert->name = $request->name;
    $insert->gaji = str_replace(['Rp ','.',',00'],'',$request->gaji);
    $insert->menit = $request->menit;
    $insert->jadwal_id = $request->jadwal;
    $insert->lembur = $request->lembur;

    if ($insert->save()) {
      return redirect()->route('payroll.user.show',['uuid'=>$user->uuid])->with('message','Data berhasil disimpan!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  /**
  * Show the specified resource.
  * @param int $id
  * @return Response
  */
  public function show($uuid)
  {
    $user = User::where('uuid',$uuid)->first();

    if (request()->ajax()) {
      $data = Payroll::orderBy('name','asc');
      return DataTables::of($data)
      ->addColumn('get_jadwal',function($row){
        $jadwal = $row->jadwal->select('nama_jadwal')
        ->get()
        ->pluck('nama_jadwal')->toArray();
        return implode(', ',$jadwal);
      })
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('payroll.user.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('payroll.user.destroy',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus data '.$row->name.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action','desc'])
      ->make(true);
    }

    $data = [
      'title' => $this->title,
      'subtitle' => $user->name,
      'user' => $user
    ];
    return view('payroll::user.show',$data);
  }

  /**
  * Show the form for editing the specified resource.
  * @param int $id
  * @return Response
  */
  public function edit($uuid)
  {
    if (request()->ajax()) {
      $jadwal = Jadwal::whereHas('user',function($q){
        $q->where('id',request()->user);
      })
      ->with('get_ruang')
      ->get();
      return response()->json($jadwal);
    }

    $payroll = Payroll::where('uuid',$uuid)->with('user')->first();

    $data = [
      'title' => $this->title,
      'subtitle' => 'Ubah Data Gaji',
      'data' => $payroll
    ];
    return view('payroll::user.edit',$data);
  }

  /**
  * Update the specified resource in storage.
  * @param Request $request
  * @param int $id
  * @return Response
  */
  public function update(Request $request, $uuid)
  {
    $role = [
      'user' => 'required',
      'name' => 'required',
      'gaji' => 'required',
      'menit' => 'required',
      'jadwal' => 'required'
    ];
    $msgs = [
      'user.required' => 'User tidak boleh kosong!',
      'name.required' => 'Nama gaji tidak boleh kosong!',
      'gaji.required' => 'Jumlah gaji tidak boleh kosong!',
      'menit.required' => 'Menit tidak boleh kosong!',
      'jadwal.required' => 'Jadwal harus dipilih!'
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $user = User::find($request->user);

    $insert = Payroll::where('uuid',$uuid)->first();
    $insert->name = $request->name;
    $insert->gaji = str_replace(['Rp ','.',',00'],'',$request->gaji);
    $insert->menit = $request->menit;
    $insert->jadwal_id = $request->jadwal;
    $insert->lembur = $request->lembur;

    if ($insert->save()) {
      return redirect()->route('payroll.user.show',['uuid'=>$user->uuid])->with('message','Data berhasil disimpan!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  /**
  * Remove the specified resource from storage.
  * @param int $id
  * @return Response
  */
  public function destroy($uuid)
  {
    $payroll = Payroll::where('uuid',$uuid)->first();
    $rollcount = Payroll::where('user_id',$payroll->user_id)->count();
    if ($payroll->delete()) {
      if ($rollcount > 1) {
        return redirect()->back()->with('message','Data berhasil dihapus!');
      }else {
        return redirect()->route('payroll.user.index')->with('message','Data berhasil dihapus!');
      }
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  public function reset($uuid)
  {
    $user = User::where('uuid',$uuid)->first();

    if ($user->payroll()->delete()) {
      return redirect()->back()->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }
}
