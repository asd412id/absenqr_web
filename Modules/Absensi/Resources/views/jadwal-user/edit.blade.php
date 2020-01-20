@extends('layouts.master')
@section('title',$title)
@section('header')
  <style media="screen">
    .table td{
      white-space: normal !important;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-users bg-warning"></i>
@endsection
@section('head_title','Jadwal Absen '.$data->name)
@section('head_desc','Ubah Data Jadwal Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.jadwal.index') }}">{{ 'Jadwal Absen User' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
@php
$data_jadwal = $data->jadwal()->select('id')->get()->pluck('id')->toArray();
@endphp
<form action="{{ route('absensi.jadwal.user.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div id="jadwal-wrapper">
      @foreach ($data_jadwal as $key => $jd)
        <input type="hidden" name="jadwal_user[]" value="{{ $jd }}" id="jadwal_{{ $jd}}">
      @endforeach
    </div>
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <table class="table table-hover table-striped nowrap" id="table-absensi-edit-jadwal-user">
            <thead>
              <th width="10">#</th>
              <th>Nama Jadwal</th>
              <th>Ruang</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Terlambat (Menit)</th>
              <th>Pulang Cepat (Menit)</th>
              <th>Hari</th>
              <th width="10">
                <label class="checkbox-inline">
                  <input type="checkbox" id="select-all">
                  Pilih
                </label>
              </th>
            </thead>
            <tbody>
              @foreach ($jadwal as $key => $v)
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td>{{ $v->nama_jadwal }}</td>
                  <td>{{ $v->get_ruang->nama_ruang }}</td>
                  <td>{{ $v->cin }}</td>
                  <td>{{ $v->cout }}</td>
                  <td>{{ $v->late }}</td>
                  <td>{{ $v->early }}</td>
                  <td>{{ implode(", ",$v->nama_hari) }}</td>
                  <td>
                    <input type="checkbox" class="check_jadwal" data-target="jadwal_{{ $v->id }}" data-id="{{ $v->id }}" {{ in_array($v->id,$data_jadwal) ?'checked':''}}>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
            <a href="{{ route('absensi.jadwal.user.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('footer')
<script src="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.full.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  function checkBox() {
    $(".check_jadwal").each(function(i,v){
      if (!$(v).is(":checked")) {
        $("#select-all").prop('checked',false);
        return false;
      }
      $("#select-all").prop('checked',true);
    })

    $(".check_jadwal").each(function(i,e){
      let target = $(e).data('target');
      let v = $(e).data('id');
      if ($(e).is(":checked")) {
        let w = '<input type="hidden" name="jadwal_user[]" value="'+v+'" id="'+target+'" />';
        let isSet = $("#jadwal-wrapper").find("#"+target).length;
        if (isSet == 0) {
          $("#jadwal-wrapper").append(w);
        }
      }else{
        $("#jadwal-wrapper").find("#"+target).remove();
      }
    })

  }
  $(".check_jadwal").on('change',function(){
    checkBox();
  })
  $("#select-all").change(function(){
    if ($(this).is(":checked")) {
      $(".check_jadwal").prop('checked',true).change();
    }else{
      $(".check_jadwal").prop('checked',false).change();
    }
  })

  checkBox();

  $(".timepicker").datetimepicker({
    datepicker:false,
    format:'H:i',
    step:5
  });
  @if ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
