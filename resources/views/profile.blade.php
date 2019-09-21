@extends('arsip::layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ asset('assets/vendor/datetimepicker/css/datetimepicker.min.css') }}">
@endsection
@section('head_icon')
  <i class="ik ik-settings bg-red"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Pengaturan informasi login Administrator')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">Pengaturan Akun</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
            @foreach ($errors->all() as $key => $err)
              <div class="text-danger font-weight-bold text-center">{{ $err }}</div>
            @endforeach
          @elseif (session()->get('message'))
            <div class="text-success font-weight-bold text-center">{{ session()->get('message') }}</div>
          @endif
          <form class="" action="{{ route('profile.update') }}" method="post">
            @csrf
            <div class="row form-group">
              <label for="" class="control-label col-sm-12">Nama</label>
              <div class="col-sm-12">
                <input type="text" name="name" class="form-control" value="{{ $data->name }}" required>
              </div>
            </div>
            <div class="row form-group">
              <label for="" class="control-label col-sm-12">Username</label>
              <div class="col-sm-12">
                <input type="text" name="username" class="form-control" value="{{ $data->username }}" required>
              </div>
            </div>
            <div class="row form-group">
              <label for="" class="control-label col-sm-12">Password Lama</label>
              <div class="col-sm-12">
                <input type="password" name="old_password" class="form-control" required>
              </div>
            </div>
            <div class="row form-group">
              <label for="" class="control-label col-sm-12">Password Baru</label>
              <div class="col-sm-12">
                <input type="password" name="new_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password ...">
              </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
