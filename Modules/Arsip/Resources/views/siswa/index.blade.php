@extends('arsip::layouts.master')
@section('title',$title)

@section('head_icon')
  <i class="ik ik-users bg-green"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Informasi Mengenai Data Siswa')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection
@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header clearfix cd-title">
        <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-primary mr-1"><i class="ik ik-user-plus"></i> Tambah Data</a>
        <a href="javascript:void(0)" class="btn btn-sm btn-success btn-print" target="_blank"><i class="fas fa-file-pdf"></i> Expor PDF</a>
      </div>
      <div class="card-body">
        <div class="dt-responsive">
          <table class="table table-hover table-striped nowrap" id="table-siswa">
            <thead>
              <th>NISN</th>
              <th>NIS</th>
              <th>Nama</th>
              <th>Jenis Kelamin</th>
              <th>Tempat, Tanggal Lahir</th>
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
