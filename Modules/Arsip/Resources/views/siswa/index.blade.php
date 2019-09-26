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
        <a href="javascript:void()" data-toggle="modal" data-target="#importModal" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i class="fas fa-file-excel text-white-50"></i> Impor Excel</a>
        <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-print" target="_blank"><i class="fas fa-file-pdf"></i> Expor PDF</a>
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
              <th>Asal Sekolah</th>
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

@section('modals')
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import File Excel (*.xlsx, *.xls, *.ods)</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form id="form-import" action="{{ route('siswa.import.excel') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <input type="file" name="file_excel" class="file-upload-default" accept=".xlsx,.xls,.ods" required>
            <div class="input-group col-xs-12">
              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload File Excel">
              <span class="input-group-append">
                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
              </span>
            </div>
          </div>
          <div class="mt-2">
            <label for="update">
              <input type="radio" name="status" value="update" id="update" checked>
              Update Data <em>(Meperbaharui data lama)</em>
            </label>
            <label for="new">
              <input type="radio" name="status" value="new" id="new">
              Data Baru <em class="text-danger">(Data lama akan diganti dengan data dari file excel)</em>
            </label>
          </div>
          <div class="mt-1 text-center">
            <div class="alert alert-success">
              <em>Format file Excel harus sesuai dengan template! Silahkan <a href="{{ route('siswa.download.template.excel') }}" class="text-danger font-weight-bold">download template</a> berikut untuk menginput data.</em>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="fas fa-file-upload"></i> Import File</button>
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
        </div>
      </form>
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
