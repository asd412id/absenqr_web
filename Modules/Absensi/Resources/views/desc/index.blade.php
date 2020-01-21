@extends('layouts.master')
@section('title',$title)
@section('header')
  <style media="screen">
    .table td{
      white-space: normal !important;
    }
    .table td:nth-child(6){
      white-space: nowrap !important;
    }
    .table td .badge{
      margin: 1px 0;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-edit bg-info"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Daftar Keterangan Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection
@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header clearfix cd-title">
        @if (\Auth::user()->role == 'admin')
          <a href="{{ route('absensi.desc.create') }}" class="btn btn-sm mr-1 btn-primary"><i class="ik ik-plus"></i> Tambah Data</a>
        @endif
      </div>
      <div class="card-body">
        <div class="dt-responsive">
          <table class="table table-hover table-striped nowrap" id="table-absensi-desc">
            <thead>
              <th width="10">#</th>
              <th>Nama User</th>
              <th>Tanggal</th>
              <th>Keterangan</th>
              <th width="115">Jadwal</th>
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
