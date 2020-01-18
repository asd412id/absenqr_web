@extends('layouts.master')
@section('title',$title)
@section('header')
  <style media="screen">
    .table td:nth-child(8){
      white-space: normal !important;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-clock bg-success"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Daftar Jadwal Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection
@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header clearfix cd-title">
        @if (\Auth::user()->role == 'admin')
          <a href="{{ route('absensi.jadwal.create') }}" class="btn btn-sm mr-1 btn-primary"><i class="ik ik-plus"></i> Tambah Data</a>
        @endif
      </div>
      <div class="card-body">
        <div class="dt-responsive">
          <table class="table table-hover table-striped nowrap" id="table-absensi-jadwal">
            <thead>
              <th width="10">#</th>
              <th>Nama Jadwal</th>
              <th>Ruang</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Terlambat (Menit)</th>
              <th>Pulang Cepat (Menit)</th>
              <th>Hari</th>
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
