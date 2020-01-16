@extends('layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.min.css') }}">
  <style media="screen">
    .btn-cari{
      position: relative;
      top: 3px;
    }
    .nowrap{
      white-space: nowrap;
    }
    .table thead th{
      vertical-align: middle;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-clipboard-list bg-danger"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Daftar Log Absen')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection
@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('absensi.log.show') }}" method="post">
          @csrf
          <div class="row">
            <div class="col-sm-2">
              <select class="form-control" name="user" id="user">
                <option {{ !request()->user?'selected':'' }} value="">Semua User</option>
                @foreach ($users as $key => $v)
                  <option {{ $v->uuid==request()->user?'selected':'' }} value="{{ $v->uuid }}">{{ $v->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-2">
              <select class="form-control" name="role" id="role">
                <option {{ !request()->role?'selected':'' }} value="">Semua Role</option>
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
            <div class="col-sm-3">
              <div class="input-group" id="range">
                <input type="text" class="form-control" value="{{ request()->start_date??date('Y/m/d') }}" name="start_date" id="start_date">
                <div class="input-group-append">
                  <span class="input-group-text">--</span>
                </div>
                <input type="text" class="form-control" value="{{ request()->end_date??date('Y/m/d') }}" name="end_date" id="end_date">
              </div>
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary btn-cari" onclick="$(this).closest('form').prop('target',false)">Proses</button>
              @if (@count($data))
                <input type="submit" name="download_pdf" value="Dowload" class="btn btn-danger" style="position: relative;top: 3px" onclick="$(this).closest('form').prop('target','_blank')">
              @endif
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 text-center">
              @foreach ($jadwal as $key => $v)
                <label class="checkbox-inline" style="margin: 5px;margin-bottom: 15px">
                  <input {{ request()->jadwal&&in_array($v->uuid,request()->jadwal)?'checked':'' }} {{ !request()->jadwal?'checked':'' }} type="checkbox" name="jadwal[]" value="{{ $v->uuid }}">
                  <span style="position: relative;top: -2px">{{ $v->nama_jadwal.' ('.$v->get_ruang->nama_ruang.')' }}</span>
                </label>
              @endforeach
              {{-- <select class="form-control" name="jadwal" id="jadwal">
                <option {{ !request()->jadwal?'selected':'' }} value="">Semua Jadwal</option>
                @foreach ($jadwal as $key => $v)
                  <option {{ $v->uuid==request()->jadwal?'selected':'' }} value="{{ $v->uuid }}">{{ $v->nama_jadwal }}</option>
                @endforeach
              </select> --}}
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
  $(function(){
   $('#start_date').datetimepicker({
    format:'Y/m/d',
    onShow:function( ct ){
      let end_date = $('#end_date').val();
      this.setOptions({
        maxDate:end_date?end_date:false
      })
    },
    timepicker:false
   });
   $('#end_date').datetimepicker({
    format:'Y/m/d',
    onShow:function( ct ){
      let start_date = $('#start_date').val();
      this.setOptions({
        minDate:start_date?start_date:false
      })
    },
    timepicker:false
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
