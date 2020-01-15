@extends('layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.min.css') }}">
@endsection
@section('head_icon')
  <i class="fas fa-clock bg-success"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Ubah Data Jadwal Absensi')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.jadwal.index') }}">{{ 'Jadwal Absensi' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
<form action="{{ route('absensi.jadwal.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Ubah Data Jadwal Absensi</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="nama_jadwal">Nama Jadwal</label>
            <input type="text" name="nama_jadwal" class="form-control" id="nama_jadwal" value="{{ $data->nama_jadwal }}" placeholder="Nama Jadwal" required>
          </div>
          <div class="form-group">
            <label for="ruang">Ruang</label>
            <select class="form-control" name="ruang" required>
              <option value="">Pilih Ruang</option>
              @foreach ($ruang as $key => $r)
                <option {{ $data->ruang == $r->id ? 'selected' : '' }} value="{{ $r->id }}">{{ $r->nama_ruang }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="cin">Check In</label>
            <input type="text" name="cin" class="form-control timepicker" id="cin" value="{{ $data->cin }}" required>
          </div>
          <div class="form-group">
            <label for="cout">Check Out</label>
            <input type="text" name="cout" class="form-control timepicker" id="cout" value="{{ $data->cout }}" required>
          </div>
          <div class="form-group">
            <label for="late">Terlambat (Menit)</label>
            <input type="number" name="late" class="form-control" id="late" value="{{ $data->late }}" required>
          </div>
          <div class="form-group">
            <label for="early">Pulang Cepat (Menit)</label>
            <input type="number" name="early" class="form-control" id="early" value="{{ $data->early }}">
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Pengaturan Tambahan</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="start_cin">Mulai Check In</label>
            <input type="text" name="start_cin" class="form-control timepicker" id="start_cin" value="{{ $data->start_cin }}" required>
          </div>
          <div class="form-group">
            <label for="start_cout">Selesai Check In</label>
            <input type="text" name="end_cin" class="form-control timepicker" id="end_cin" value="{{ $data->end_cin }}" required>
          </div>
          <div class="form-group">
            <label for="start_cout">Mulai Check Out</label>
            <input type="text" name="start_cout" class="form-control timepicker" id="start_cout" value="{{ $data->start_cout }}" required>
          </div>
          <div class="form-group">
            <label for="end_cout">Selesai Check Out</label>
            <input type="text" name="end_cout" class="form-control timepicker" id="end_cout" value="{{ $data->end_cout }}" required>
          </div>
          <div class="form-group">
            <label for="end_cout">Pilih Hari</label>
            @php
              $hari = [7=>'Ahad',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jum\'at',6=>'Sabtu'];
            @endphp
            <div class="row">
              @foreach ($hari as $key => $h)
                <div class="col-sm-3">
                  <label>
                    <input type="checkbox" name="hari[]" value="{{ $key }}" {{ in_array($key,$data->hari)?'checked':'' }}>
                    <span style="position: relative;top: -2px;">{{ $h }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12 text-center">
      <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
      <a href="{{ route('absensi.jadwal.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
    </div>
  </div>
</form>
@endsection

@section('footer')
<script src="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.full.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  $(".timepicker").datetimepicker({
    datepicker:false,
    format:'H:i',
    step:5
  });
  @if ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
