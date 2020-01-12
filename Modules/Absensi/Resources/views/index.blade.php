@extends('layouts.master')
@section('title','Status Arsip')
@section('head_icon')
  <i class="ik ik-bar-chart-2 bg-blue"></i>
@endsection
@section('head_title','Status Arsip')
@section('head_desc','Informasi Umum Data Arsip')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">Status Arsip</li>
@endsection
@section('content')
<div class="row clearfix">
  <div class="col-lg-3 col-md-6 col-sm-12">
    <div class="widget bg-primary">
      <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="state">
            <h6>Siswa</h6>
            <h2>{{ $siswa }}</h2>
          </div>
          <div class="icon">
            <i class="ik ik-users"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-12">
    <div class="widget bg-warning">
      <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="state">
            <h6>PNS</h6>
            <h2>{{ $pns }}</h2>
          </div>
          <div class="icon">
            <i class="fas fa-graduation-cap"></i>
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
            <h6>GTT</h6>
            <h2>{{ $gtt }}</h2>
          </div>
          <div class="icon">
            <i class="fas fa-user-graduate"></i>
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
            <h6>PTT</h6>
            <h2>{{ $ptt }}</h2>
          </div>
          <div class="icon">
            <i class="fas fa-user-tie"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
