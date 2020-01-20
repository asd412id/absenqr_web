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
      max-width: 50%;
    }
    .modal table td{
      white-space: normal !important;
    }
    label{
      margin-bottom: 0;
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
                  <span class="input-group-text">ke</span>
                </div>
                <input type="text" class="form-control" value="{{ request()->end_date??date('Y/m/d') }}" name="end_date" id="end_date">
              </div>
            </div>
            <div class="col-sm-3">
              <a href="javascript:void()" data-toggle="modal" data-target="#showjadwal" class="btn btn-success btn-cari"> Jadwal</a>
              <button type="submit" class="btn btn-primary btn-cari" onclick="$(this).closest('form').prop('target','_self')">Proses</button>
              @if (@count($data))
                <input type="submit" name="download_pdf" value="Dowload" class="btn btn-danger" style="position: relative;top: 3px" onclick="$(this).closest('form').prop('target','_blank')">
              @endif
            </div>
          </div>
          <div class="row" id="jadwal-wrapper" style="display: none">
            @php
              $list_jadwal = request()->jadwal??$jadwal;
            @endphp
            @foreach ($list_jadwal as $key => $jd)
              <input type="hidden" name="jadwal[]" value="{{ $jd->uuid??$jd }}" id="jadwal_{{ $jd->uuid??$jd}}">
            @endforeach
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
@section('modals')
  <div class="modal fade" id="showjadwal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="daftarjadwal-title">Filter Jadwal</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <table class="table table-hover table-striped nowrap" id="table-absensi-edit-jadwal-user">
                  <thead>
                    <th width="10">#</th>
                    <th>Nama Jadwal</th>
                    <th>Ruang</th>
                    <th>Hari</th>
                    <th width="10">
                      <label class="checkbox-inline">
                        <input type="checkbox" id="select-all">
                        <span style="position: relative;top: -2px;">Pilih</span>
                      </label>
                    </th>
                  </thead>
                  <tbody>
                    @foreach ($jadwal as $key => $v)
                      <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $v->nama_jadwal }}</td>
                        <td>{{ $v->get_ruang->nama_ruang }}</td>
                        <td>{{ implode(", ",$v->nama_hari) }}</td>
                        <td>
                          <input type="checkbox" class="daftar_jadwal" data-target="jadwal_{{ $v->uuid }}" data-id="{{ $v->uuid }}" {{ request()->jadwal&&in_array($v->uuid,request()->jadwal)?'checked':'' }} {{ !request()->jadwal?'checked':'' }}>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
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
@endsection
@section('footer')
<script src="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.full.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  function checkBox() {
    $(".daftar_jadwal").each(function(i,v){
      if (!$(v).is(":checked")) {
        $("#select-all").prop('checked',false);
        return false;
      }
      $("#select-all").prop('checked',true);
    })

    $(".daftar_jadwal").each(function(i,e){
      let target = $(e).data('target');
      let v = $(e).data('id');
      if ($(e).is(":checked")) {
        let w = '<input type="hidden" name="jadwal[]" value="'+v+'" id="'+target+'" />';
        let isSet = $("#jadwal-wrapper").find("#"+target).length;
        if (isSet == 0) {
          $("#jadwal-wrapper").append(w);
        }
      }else{
        $("#jadwal-wrapper").find("#"+target).remove();
      }
    })

  }
  $(".daftar_jadwal").on('change',function(){
    checkBox();
  })
  $("#select-all").change(function(){
    if ($(this).is(":checked")) {
      $(".daftar_jadwal").prop('checked',true).change();
    }else{
      $(".daftar_jadwal").prop('checked',false).change();
    }
  })
  checkBox();

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
