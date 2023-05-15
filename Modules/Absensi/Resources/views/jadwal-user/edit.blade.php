@extends('layouts.master')
@section('title', $title)
@section('header')
  <style media="screen">
    .table td {
      white-space: normal !important;
    }

    label {
      margin-bottom: 0;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-users bg-warning"></i>
@endsection
@section('head_title', 'Jadwal Absen ' . $data->name)
@section('head_desc', 'Ubah Data Jadwal Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.jadwal.index') }}">{{ 'Jadwal Absen User' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
  <form action="{{ route('absensi.jadwal.user.update', ['uuid' => $data->uuid]) }}" enctype="multipart/form-data"
    method="post">
    @csrf
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header clearfix cd-title">
            @if (\Auth::user()->role == 'admin')
              <a href="{{ route('absensi.jadwal.byuser', ['uuid' => $data->uuid]) }}"
                class="btn btn-sm mr-1 btn-primary"><i class="ik ik-plus"></i> Buat Jadwal</a>
              <a href="javascript:void()" data-toggle="modal" data-target="#importModal"
                class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-1"><i
                  class="fas fa-file-excel text-white-50"></i> Impor Excel</a>
            @endif
          </div>
          <div class="card-body">
            <select class="form-control select2-multiple" data-url="{{ route('ajax.search.jadwal') }}"
              data-placeholder="Ketik nama jadwal atau nama ruang" style="width: 100%" name="jadwal_user[]"
              id="jadwal_user" multiple="multiple">
              <option></option>
              @if (count($data->jadwal))
                @foreach ($data->jadwal as $key => $value)
                  <option selected value="{{ $value->id }}">
                    {{ $value->nama_jadwal . ($value->alias ? ' (' . $value->alias . ')' : '') . ' - ' . $value->get_ruang->nama_ruang }}
                  </option>
                @endforeach
              @endif
            </select>
            <div class="text-center mt-10">
              <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
              <a href="{{ route('absensi.jadwal.user.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i>
                KEMBALI</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@if (\Auth::user()->role == 'admin')
  @section('modals')
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Import File Excel (*.xlsx, *.xls, *.ods)</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form id="form-import" action="{{ route('absensi.jadwal.user.import', ['uuid' => $data->uuid]) }}"
            method="post" enctype="multipart/form-data">
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
              <div class="mt-1 text-center">
                <div class="alert alert-success">
                  <em>Format file Excel harus sesuai dengan template! Silahkan <a
                      href="{{ route('absensi.jadwal.user.template', ['uuid' => $data->uuid]) }}"
                      class="text-danger font-weight-bold">download
                      template</a> berikut untuk menginput data.</em>
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
@endif

@section('footer')
  <script type="text/javascript">
    @if ($errors->any())
      @foreach ($errors->all() as $key => $err)
        showDangerToast('{{ $err }}')
      @endforeach
    @endif
  </script>
@endsection
