@extends('layouts.master')
@section('title',$title)
@section('header')
<link rel="stylesheet" href="{{ asset('assets/vendor/datetimepicker/css/datetimepicker.min.css') }}">
@endsection
@section('head_icon')
  <i class="ik ik-users bg-green"></i>
@endsection
@section('head_title',$title)
@section('head_desc',$data->nis.' - '.$data->nama_lengkap)
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Data Siswa</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $data->nama_lengkap }}</li>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    @include('arsip::siswa.show.data-pribadi')
  </div>
  <div class="col-md-4">
    @include('arsip::siswa.show.data-ayah')
  </div>
  <div class="col-md-4">
    @include('arsip::siswa.show.data-ibu')
  </div>
  <div class="col-md-4">
    @include('arsip::siswa.show.data-wali')
  </div>
  <div class="col-sm-6">
    @include('arsip::siswa.show.data-sekolah')
  </div>
  <div class="col-sm-6">
    @include('arsip::siswa.show.data-kesehatan')
  </div>
</div>
@endsection
