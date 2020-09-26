<?php

namespace Modules\Absensi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Absensi\Entities\Ruang;

use DataTables;
use Validator;
use Str;
use Storage;
use GuzzleHttp\Client;
use PDF;

class RuangController extends Controller
{
  /**
  * Display a listing of the resource.
  * @return Response
  */
  public function index()
  {
    if (request()->ajax()) {
      $data = Ruang::orderBy('nama_ruang','asc');
      return DataTables::of($data)
      ->addColumn('action', function($row){

        $btn = '<div class="table-actions">';

        if (\Auth::user()->role == 'admin') {
          $btn .= ' <a href="'.route('absensi.ruang.export.pdf',['uuid'=>$row->uuid]).'" class="text-danger" title="Export PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>';

          $btn .= ' <a href="'.route('absensi.ruang.edit',['uuid'=>$row->uuid]).'" class="text-primary" title="Ubah"><i class="ik ik-edit"></i></a>';

          $btn .= ' <a href="'.route('absensi.ruang.destroy',['uuid'=>$row->uuid]).'" class="text-danger confirm" data-text="Hapus data '.$row->nama_ruang.'?" title="Hapus"><i class="ik ik-trash-2"></i></a>';
        }

        $btn .= '</div>';

        return $btn;
      })
      ->rawColumns(['action','desc'])
      ->make(true);
    }

    $data = ['title'=>'Ruang Absensi'];

    return view('absensi::ruang.index',$data);
  }

  /**
  * Show the form for creating a new resource.
  * @return Response
  */
  public function create()
  {
    $data = ['title'=>'Tambah Ruang Absensi'];
    return view('absensi::ruang.create',$data);
  }

  /**
  * Store a newly created resource in storage.
  * @param Request $request
  * @return Response
  */
  public function store(Request $request)
  {
    $role = [
      'nama_ruang' => 'required',
    ];
    $msgs = [
      'nama_ruang.required' => 'Nama ruang absensi tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = new Ruang;
    $insert->uuid = (string) Str::uuid();
    $insert->nama_ruang = $request->nama_ruang;
    $insert->desc = $request->desc;
    $insert->_token = Str::random(100);

    if ($insert->save()) {
      return redirect()->route('absensi.ruang.index')->with('message','Data berhasil disimpan!');
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
    $ruang = Ruang::where('uuid',$uuid)->first();
    if (!$ruang) {
      return redirect()->route('absensi.ruang.index');
    }
    $data = [
      'title'=>'Detail Data Guru & Ruang',
      'data'=>$ruang
    ];
    return view('absensi::ruang.show',$data);
  }

  /**
  * Show the form for editing the specified resource.
  * @param int $uuid
  * @return Response
  */
  public function edit($uuid)
  {
    $ruang = Ruang::where('uuid',$uuid)->first();
    if (!$ruang) {
      return redirect()->route('absensi.ruang.index');
    }
    $data = [
      'title'=>'Ubah Ruang Absensi',
      'data'=>$ruang
    ];
    return view('absensi::ruang.edit',$data);
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
      'nama_ruang' => 'required',
    ];
    $msgs = [
      'nama_ruang.required' => 'Nama ruang absensi tidak boleh kosong!',
    ];

    Validator::make($request->all(),$role,$msgs)->validate();

    $insert = Ruang::where('uuid',$uuid)->first();
    $insert->nama_ruang = $request->nama_ruang;
    $insert->desc = $request->desc;

    if ($insert->save()) {
      return redirect()->route('absensi.ruang.index')->with('message','Data berhasil disimpan!');
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
    $ruang = Ruang::where('uuid',$uuid)->first();
    if ($ruang->foto) {
      Storage::disk('public')->delete($ruang->foto);
    }
    $ruang->jadwal()->delete();
    $ruang->absen()->delete();
    $ruang->absenUser()->detach();
    if ($ruang->delete()) {
      return redirect()->route('absensi.ruang.index')->with('message','Data berhasil dihapus!');
    }
    return redirect()->back()->withErrors(['Terjadi kesalahan! Silahkan hubungi operator.'])->withInput();
  }

  public function exportPDF($uuid)
  {
    $ruang = Ruang::where('uuid',$uuid)->first();
    $data = [
      'title'=>$ruang->nama_ruang,
      'data'=>$ruang
    ];
    $params = [
      'page-width'=>'21.5cm',
      'page-height'=>'33cm',
    ];

    $filename = $data['title'].'.pdf';

    $pdf = PDF::loadView('absensi::ruang.print',$data)
    ->setOptions($params);
    return $pdf->stream($filename);

    // $view = view('absensi::ruang.print',$data)->render();
    // $client = new Client;
    // $res = $client->request('POST','http://pdf/pdf',[
    //   'form_params'=>[
    //     'html'=>str_replace(url('/'),'http://nginx_arsip/',$view),
    //     'options[page-width]'=>'21cm',
    //     'options[page-height]'=>'29.7cm',
    //   ]
    // ]);
    //
    // if ($res->getStatusCode() == 200) {
    //   $filename = $data['title'].'.pdf';
    //   return response()->attachment($res->getBody()->getContents(),$filename,'application/pdf');
    // }
    // return redirect()->back()->withErrors(['Tidak dapat mendownload file! Silahkan hubungi operator']);
  }

}
