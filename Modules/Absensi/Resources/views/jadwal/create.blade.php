@extends('layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.min.css') }}">
@endsection
@section('head_icon')
  <i class="fas fa-clock bg-success"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Tambah Data Jadwal Absensi Baru')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.jadwal.index') }}">{{ 'Jadwal Absensi' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
<form action="{{ route('absensi.jadwal.store') }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Data Jadwal Absensi Baru</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="ruang">Ruang</label>
            <select class="form-control" name="ruang" required>
              <option value="">Pilih Ruang</option>
              @foreach ($ruang as $key => $r)
                <option value="{{ $r->id }}">{{ $r->nama_ruang }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="nama_jadwal">Nama Jadwal</label>
            <input type="text" name="nama_jadwal" class="form-control" id="nama_jadwal" value="{{ old('nama_jadwal') }}" placeholder="Nama Jadwal" required>
          </div>
          <div class="form-group">
            <label for="cin">Check In</label>
            <input type="text" name="cin" class="form-control timepicker" id="cin" value="{{ old('cin') }}" required>
          </div>
          <div class="form-group">
            <label for="cout">Check Out</label>
            <input type="text" name="cout" class="form-control timepicker" id="cout" value="{{ old('cout') }}" required>
          </div>
          <div class="form-group">
            <label for="late">Terlambat (Menit)</label>
            <input type="number" name="late" class="form-control" id="late" value="{{ old('late') }}" required>
          </div>
          <div class="form-group">
            <label for="early">Pulang Cepat (Menit)</label>
            <input type="number" name="early" class="form-control" id="early" value="{{ old('early') }}">
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
            <input type="text" name="start_cin" class="form-control timepicker" id="start_cin" value="{{ old('start_cin') }}" required>
          </div>
          <div class="form-group">
            <label for="start_cout">Selesai Check In</label>
            <input type="text" name="end_cin" class="form-control timepicker" id="end_cin" value="{{ old('end_cin') }}" required>
          </div>
          <div class="form-group">
            <label for="start_cout">Mulai Check Out</label>
            <input type="text" name="start_cout" class="form-control timepicker" id="start_cout" value="{{ old('start_cout') }}" required>
          </div>
          <div class="form-group">
            <label for="end_cout">Selesai Check Out</label>
            <input type="text" name="end_cout" class="form-control timepicker" id="end_cout" value="{{ old('end_cout') }}" required>
          </div>
          <div class="form-group">
            <label for="end_cout">Pilih Hari</label>
            @php
              $hari = [7=>'Ahad',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jum\'at',6=>'Sabtu'];
            @endphp
            <div class="row">
              <div class="col-sm-3">
                <label>
                  <input type="checkbox" id="select-all">
                  <span style="position: relative;top: -2px;">Semua Hari</span>
                </label>
              </div>
              @foreach ($hari as $key => $h)
                <div class="col-sm-3">
                  <label>
                    <input type="checkbox" class="daftar_hari" name="hari[]" value="{{ $key }}">
                    <span style="position: relative;top: -2px;">{{ $h }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>
          <div class="form-group">
            <label>Jumlah Menit per Jam (60 menit, 40 menit, ...)</label>
            <input type="number" class="form-control" name="menit_per_jam" value="{{ old('menit_per_jam')??60 }}" placeholder="Jumlah menit per jam">
          </div>
          <div class="form-group">
            <label>Satuan Jam (Jam, JP, ...)</label>
            <input type="text" class="form-control" name="satuan_jam" value="{{ old('satuan_jam')??'Jam' }}" placeholder="Satuan Jam">
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
  function checkBox() {
    $(".daftar_hari").each(function(i,v){
      if (!$(v).is(":checked")) {
        $("#select-all").prop('checked',false);
        return false;
      }
      $("#select-all").prop('checked',true);
    })

    $(".daftar_hari").each(function(i,e){
      let target = $(e).data('target');
      let v = $(e).data('id');
      if ($(e).is(":checked")) {
        let w = '<input type="hidden" name="jadwal_user[]" value="'+v+'" id="'+target+'" />';
        let isSet = $("#jadwal-wrapper").find("#"+target).length;
        if (isSet == 0) {
          $("#jadwal-wrapper").append(w);
        }
      }else{
        $("#jadwal-wrapper").find("#"+target).remove();
      }
    })

  }
  $(".daftar_hari").on('change',function(){
    checkBox();
  })
  $("#select-all").change(function(){
    if ($(this).is(":checked")) {
      $(".daftar_hari").prop('checked',true).change();
    }else{
      $(".daftar_hari").prop('checked',false).change();
    }
  })

  checkBox();

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
