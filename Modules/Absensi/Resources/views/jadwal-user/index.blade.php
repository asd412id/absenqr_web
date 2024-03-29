@extends('layouts.master')
@section('title',$title)
@section('header')
  <style media="screen">
    .table td:nth-child(4){
      white-space: normal !important;
    }
    .table td .badge{
      margin: 1px 0;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-users bg-warning"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Daftar Jadwal Absen User')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection
@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <form class="mb-10 float-right" style="width: 200px" action="" method="get">
          <select class="form-control" name="user" onchange="$(this).parent().submit()">
            <option {{ request()->user=='pegawai'?'selected':'' }} value="pegawai">Pegawai</option>
            <option {{ request()->user=='siswa'?'selected':'' }} value="siswa">Siswa</option>
          </select>
        </form>
        <div class="clearfix"></div>
        <div class="dt-responsive">
          <table class="table table-hover table-striped nowrap" id="table-absensi-jadwal-absen-user">
            <thead>
              <th width="10">#</th>
              <th>Nama</th>
              <th>Status</th>
              <th width="450">Jadwal Absen</th>
              <th width="10"></th>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
@if (session()->has('message'))
<script type="text/javascript">
  showSuccessToast('{{ session()->get('message') }}')
</script>
@elseif ($errors->any())
<script type="text/javascript">
@foreach ($errors->all() as $key => $err)
  showDangerToast('{{ $err }}')
@endforeach
</script>
@endif
@endsection
