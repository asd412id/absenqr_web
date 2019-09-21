@extends('arsip::layouts.master')
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
<form action="{{ route('siswa.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      @include('arsip::siswa.edit.data-pribadi')
      @include('arsip::siswa.edit.data-sekolah')
    </div>
    <div class="col-sm-6">
      @include('arsip::siswa.edit.data-ayah')
      @include('arsip::siswa.edit.data-ibu')
      @include('arsip::siswa.edit.data-wali')
      @include('arsip::siswa.edit.data-kesehatan')
    </div>
    <div class="col-md-12 text-center">
      <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
      <a href="{{ route('siswa.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
    </div>
  </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
  $('.file-upload-browse').on('click', function() {
    var file = $(this).parent().parent().parent().find('.file-upload-default');
    file.trigger('click');
  });
  $('.file-upload-default').on('change', function() {
    $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
  });
  @if ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
