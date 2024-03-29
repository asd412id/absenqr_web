@extends('layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.min.css') }}">
  <style media="screen">
    caption{
      caption-side: top;
      color: #000;
    }
    .nowrap{
      white-space: nowrap;
    }
    .table thead th{
      vertical-align: middle;
    }
    .table-absen th, .table-absen td{
      padding: 3px 7px;
    }
    .modal .card{
      box-shadow: none !important;
      margin-bottom: 0;
    }
    .modal .card .card-body{
      padding: 0 20px;
    }
    .modal-dialog{
      max-width: 60%;
    }
    .modal table td{
      white-space: normal !important;
    }
    label{
      margin-bottom: 0;
    }
    .desc{
      max-width: 225px;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-book bg-primary"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Rekap Absen')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection
@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('absensi.log.rekap.show') }}" method="post">
          @csrf
          <div class="row">
            <div class="col-sm-3">
              <input type="text" class="form-control" value="{{ request()->title??'Rekap Absen' }}" name="title" id="title" title="Title Log Absensi">
            </div>
            <div class="col-sm-2">
              <select class="form-control" name="role" id="role">
                <option {{ request()->role=='pegawai'?'selected':'' }} value="pegawai">Pegawai</option>
                <option {{ request()->role=='siswa'?'selected':'' }} value="siswa">Siswa</option>
              </select>
            </div>
            <div class="col-sm-2">
              <select class="form-control" name="status" id="status">
                <option {{ !request()->status?'selected':'' }} value="">Semua Status</option>
                <option {{ request()->status=='pns'?'selected':'' }} value="pns">PNS</option>
                <option {{ request()->status=='gtt'?'selected':'' }} value="gtt">GTT</option>
                <option {{ request()->status=='ptt'?'selected':'' }} value="ptt">PTT</option>
              </select>
            </div>
            <div class="col-sm-5">
              <div class="input-group" id="range">
                <input type="text" class="form-control" value="{{ request()->start_date??old('start_date')??date('Y/m/d') }}" name="start_date" id="start_date">
                <div class="input-group-append">
                  <span class="input-group-text">ke</span>
                </div>
                <input type="text" class="form-control" value="{{ request()->end_date??old('end_date')??date('Y/m/d') }}" name="end_date" id="end_date">
              </div>
            </div>
            <div class="col-sm-12 mb-10 text-center">
              <a href="javascript:void()" data-toggle="modal" data-target="#showuser" class="btn btn-info btn-cari float-left mr-1"> User</a>
              <a href="javascript:void()" data-toggle="modal" data-target="#showjadwal" class="btn btn-success btn-cari float-left mr-1"> Jadwal</a>
              @if (@count($data))
                <input type="submit" name="download_pdf" value="Download" class="btn btn-danger float-right ml-1" style="position: relative" onclick="$(this).closest('form').prop('target','_blank')">
              @endif
              <button type="submit" class="btn btn-primary btn-cari float-right ml-1" onclick="$(this).closest('form').prop('target','_self')">Proses</button>
              <label class="float-right ml-1" style="line-height: 2.5em;">Skala
                <select class="form-control float-right ml-1" style="width: 80px;position: relative;top:-2px" name="font_size">
                  @foreach (range(1,25) as $key => $fz)
                    <option {{ request()->font_size==($fz*5).'%'||$fz==20?'selected':'' }} value="{{ ($fz*5).'%' }}">{{ ($fz*5).'%' }}</option>
                  @endforeach
                </select>
              </label>
            </div>
          </div>
          <div class="modal fade" id="showuser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="daftarjadwal-title">Filter User</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="card">
                      <div class="card-body">
                        <h6>Ketik nama user, kelas, role, atau status kepegawaian untuk mulai mencari! (Kosongkan untuk memilih semua user)</h6>
                        <select class="form-control select2-multiple" data-url="{{ route('ajax.search.user') }}" data-placeholder="Semua User" style="width: 100%" name="user[]" id="user" multiple>
                          @if (request()->user && count($users))
                            @foreach ($users as $key => $v)
                              <option selected value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                          @endif
                        </select>
                        <div class="text-right mt-10">
                          <button class="btn btn-outline-success" type="button" data-dismiss="modal">Simpan Pilihan</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="showjadwal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title" id="daftarjadwal-title">Filter Jadwal</h6>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="card">
                      <div class="card-body">
                        <h6>Ketik nama jadwal atau nama ruang! (Kosongkan untuk memilih semua jadwal)</h6>
                        <select class="form-control select2" data-url="{{ route('ajax.search.jadwal') }}" data-placeholder="Ketik nama jadwal atau nama ruang" style="width: 100%" name="jadwal">
                          @if (request()->jadwal && count($jadwal))
                            @foreach ($jadwal as $key => $value)
                              <option selected value="{{ $value->id }}">{{ $value->nama_jadwal.($value->alias?' ('.$value->alias.')':'').' - '.$value->get_ruang->nama_ruang }}</option>
                            @endforeach
                          @endif
                        </select>
                        <div class="text-right mt-10">
                          <button class="btn btn-outline-success" type="button" data-dismiss="modal">Simpan Pilihan</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="row">
          @yield('log-content')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
<script src="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.full.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  function formatDate(date){
     var parts = date.split("/");
     return new Date(parts[0], parts[1] - 1, parts[2]);
  }
  $(function(){
    $('#start_date').datetimepicker({
      format:'Y/m/d',
      timepicker:false
    }).on('change',function(){
      let start_date = formatDate($('#start_date').val());
      let end_date = formatDate($('#end_date').val());
      if (start_date > end_date) {
        $('#end_date').val($('#start_date').val());
      }
    });
    $('#end_date').datetimepicker({
      format:'Y/m/d',
      timepicker:false
    }).on('change',function(){
      let start_date = formatDate($('#start_date').val());
      let end_date = formatDate($('#end_date').val());
      if (start_date > end_date) {
        $('#start_date').val($('#end_date').val());
      }
    });
  });
  @if (session()->has('message'))
    showSuccessToast('{{ session()->get('message') }}')
  @elseif ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
