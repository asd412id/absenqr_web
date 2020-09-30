@extends('layouts.master')
@section('title',$title)
@section('header')
<link rel="stylesheet" href="{{ asset('assets/vendor/datetimepicker/css/datetimepicker.min.css') }}">
@endsection
@section('head_icon')
  <i class="fas fa-user-tie bg-green"></i>
@endsection
@section('head_title',$title)
@section('head_desc',($data->nuptk?$data->nuptk.' - ':'').$data->nama)
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Data Guru & Pegawai</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $data->nama }}</li>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    @include('arsip::pegawai.show.data-pribadi')
  </div>
  <div class="col-md-6">
    @include('arsip::pegawai.show.data-kesehatan')
  </div>
  <div class="col-md-6">
    @include('arsip::pegawai.show.data-pendidikan')
  </div>
</div>
@endsection
