@extends('layouts.master')
@section('title','Status Absensi')
@section('head_icon')
  <i class="ik ik-bar-chart-2 bg-blue"></i>
@endsection
@section('head_title','Status Absensi')
@section('head_desc','Informasi Umum Data Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">Status Absensi</li>
@endsection
@section('content')
<div class="row clearfix">
  <div class="col-lg-3 col-md-6 col-sm-12">
    <div class="widget bg-primary">
      <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="state">
            <h6>Ruang</h6>
            <h2>{{ $ruang }}</h2>
          </div>
          <div class="icon">
            <i class="fas fa-dungeon"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-12">
    <div class="widget bg-success">
      <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="state">
            <h6>Jadwal</h6>
            <h2>{{ $jadwal }}</h2>
          </div>
          <div class="icon">
            <i class="fas fa-clock"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-12">
    <div class="widget bg-info">
      <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="state">
            <h6>Keterangan</h6>
            <h2>{{ $desc }}</h2>
          </div>
          <div class="icon">
            <i class="fas fa-edit"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-12">
    <div class="widget bg-danger">
      <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="state">
            <h6>Logs</h6>
            <h2>{{ $logs }}</h2>
          </div>
          <div class="icon">
            <i class="fas fa-clipboard-list"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
