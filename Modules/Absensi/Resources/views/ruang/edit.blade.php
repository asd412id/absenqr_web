@extends('layouts.master')
@section('title',$title)
@section('head_icon')
  <i class="fas fa-dungeon bg-primary"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Ubah Data Ruang Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.ruang.index') }}">{{ 'Ruang Absensi' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
<form action="{{ route('absensi.ruang.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Data Ruang Absensi</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="username">Nama Ruang</label>
            <input type="text" name="nama_ruang" class="form-control" id="nama_ruang" value="{{ $data->nama_ruang }}" placeholder="Nama Ruang" required>
          </div>
          <div class="form-group">
            <label for="password">Deskripsi</label>
            <textarea name="desc" rows="3" class="form-control" placeholder="Deskripsi">{{ $data->desc }}</textarea>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
          <a href="{{ route('absensi.ruang.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('footer')
@if ($errors->any())
<script type="text/javascript">
  @foreach ($errors->all() as $key => $err)
    showDangerToast('{{ $err }}')
  @endforeach
</script>
@endif
@endsection
