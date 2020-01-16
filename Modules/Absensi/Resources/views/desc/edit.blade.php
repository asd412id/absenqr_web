@extends('layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.min.css') }}">
@endsection
@section('head_icon')
  <i class="fas fa-edit bg-info"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Ubah Keterangan Absensi '.$data->user->name)
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.desc.index') }}">{{ 'Keterangan Absensi' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
<form action="{{ route('absensi.desc.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Keterangan Absensi {{ $data->user->name }}</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="time">Tanggal</label>
            <input type="text" name="time" class="form-control datepicker" id="time" value="{{ old('time')??$data->time->format('d-m-Y') }}" required placeholder="Tanggal">
          </div>
          <div class="form-group">
            <label for="time">Keterangan</label>
            <textarea name="desc" rows="3" class="form-control" placeholder="Keterangan" required>{{ old('desc')??$data->desc }}</textarea>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
            <a href="{{ route('absensi.desc.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
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
  $(".datepicker").datetimepicker({
    timepicker:false,
    format:'d-m-Y'
  });
  @if ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
