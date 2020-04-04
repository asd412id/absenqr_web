@extends('layouts.master')
@section('title',$title)
@section('header')
  <style media="screen">
    .table td{
      white-space: normal !important;
    }
    label{
      margin-bottom: 0;
    }
  </style>
@endsection
@section('head_icon')
  <i class="fas fa-users bg-warning"></i>
@endsection
@section('head_title','Jadwal Absen '.$data->name)
@section('head_desc','Ubah Data Jadwal Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.jadwal.index') }}">{{ 'Jadwal Absen User' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
<form action="{{ route('absensi.jadwal.user.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header clearfix cd-title">
          @if (\Auth::user()->role == 'admin')
            <a href="{{ route('absensi.jadwal.byuser',['uuid'=>$data->uuid]) }}" class="btn btn-sm mr-1 btn-primary"><i class="ik ik-plus"></i> Buat Jadwal</a>
          @endif
        </div>
        <div class="card-body">
          <select class="form-control select2-multiple" data-url="{{ route('ajax.search.jadwal') }}" data-placeholder="Ketik nama jadwal atau nama ruang" style="width: 100%" name="jadwal_user[]" id="jadwal_user" multiple="multiple">
            <option></option>
            @if (count($data->jadwal))
              @foreach ($data->jadwal as $key => $value)
                <option selected value="{{ $value->id }}">{{ $value->nama_jadwal.($value->alias?' ('.$value->alias.')':'').' - '.$value->get_ruang->nama_ruang }}</option>
              @endforeach
            @endif
          </select>
          <div class="text-center mt-10">
            <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
            <a href="{{ route('absensi.jadwal.user.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
  @if ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
