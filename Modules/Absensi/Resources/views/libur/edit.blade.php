@extends('layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.min.css') }}">
  <style media="screen">
    #table-jadwal th{
      white-space: nowrap;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-calendar-times bg-danger"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Tambah Hari Libur')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.libur.index') }}">{{ 'Keterangan Absensi' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
<form action="{{ route('absensi.libur.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>{{ $data->name }}</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="ruang">Nama</label>
            <input type="text" name="name" value="{{ old('name')??$data->name }}" class="form-control">
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-6">
                <label for="start">Tanggal Mulai</label>
                <input type="text" name="start" class="form-control" id="start" value="{{ old('start')??$data->start_data }}" required placeholder="Tanggal Mulai">
              </div>
              <div class="col-sm-6">
                <label for="end">Tanggal Selesai</label>
                <input type="text" name="end" class="form-control" id="end" value="{{ old('end')??$data->end_data }}" required placeholder="Tanggal Selesai">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="desc">Keterangan</label>
            <textarea name="desc" rows="8" class="form-control" placeholder="Keterangan" required>{{ old('desc')??$data->desc }}</textarea>
          </div>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
            <a href="{{ route('absensi.libur.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('footer')
<script src="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.full.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  function formatDate(date){
     var parts = date.split("/");
     return new Date(parts[0], parts[1] - 1, parts[2]);
  }
  $(function(){
    $('#start').datetimepicker({
      format:'Y/m/d',
      timepicker:false
    }).on('change',function(){
      let start = formatDate($('#start').val());
      let end = formatDate($('#end').val());
      if (start > end) {
        $('#end').val($('#start').val());
      }
    });
    $('#end').datetimepicker({
      format:'Y/m/d',
      timepicker:false
    }).on('change',function(){
      let start = formatDate($('#start').val());
      let end = formatDate($('#end').val());
      if (start > end) {
        $('#start').val($('#end').val());
      }
    });
  });

  @if ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
