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
          <h3>Pengaturan Umum</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="ruang">Ruang</label>
            <select class="form-control" name="ruang" required>
              <option value="">Pilih Ruang</option>
              @foreach ($ruang as $key => $r)
                <option {{ @$data->ruang == $r->id ? 'selected' : '' }} value="{{ $r->id }}">{{ $r->nama_ruang }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="nama_jadwal">Nama Jadwal</label>
            <input type="text" name="nama_jadwal" class="form-control" id="nama_jadwal" value="{{ @$data->nama_jadwal }}" placeholder="Nama Jadwal" required>
          </div>
          <div class="form-group">
            <label for="cin">Check In</label>
            <input type="text" name="cin" class="form-control timepicker" id="cin" value="{{ @$data->cin }}" required>
          </div>
          <div class="form-group">
            <label for="cout">Check Out</label>
            <input type="text" name="cout" class="form-control timepicker" id="cout" value="{{ @$data->cout }}" required>
          </div>
          <div class="form-group">
            <label for="late">Terlambat (Menit)</label>
            <input type="number" name="late" class="form-control" id="late" value="{{ @$data->late }}" required>
          </div>
          <div class="form-group">
            <label for="early">Pulang Cepat (Menit)</label>
            <input type="number" name="early" class="form-control" id="early" value="{{ @$data->early }}">
          </div>
          <div class="form-group">
            <label>Jumlah Menit per Jam (60 menit, 40 menit, ...)</label>
            <input type="number" class="form-control" name="menit_per_jam" value="{{ @$data->menit_per_jam??60 }}" placeholder="Jumlah menit per jam">
          </div>
          <div class="form-group">
            <label>Satuan Jam (Jam, JP, ...)</label>
            <input type="text" class="form-control" name="satuan_jam" value="{{ @$data->satuan_jam??'Jam' }}" placeholder="Satuan Jam">
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
            <input type="text" name="start_cin" class="form-control timepicker" id="start_cin" value="{{ @$data->start_cin }}" required>
          </div>
          <div class="form-group">
            <label for="start_cout">Selesai Check In</label>
            <input type="text" name="end_cin" class="form-control timepicker" id="end_cin" value="{{ @$data->end_cin }}" required>
          </div>
          <div class="form-group">
            <label for="start_cout">Mulai Check Out</label>
            <input type="text" name="start_cout" class="form-control timepicker" id="start_cout" value="{{ @$data->start_cout }}" required>
          </div>
          <div class="form-group">
            <label for="end_cout">Selesai Check Out</label>
            <input type="text" name="end_cout" class="form-control timepicker" id="end_cout" value="{{ @$data->end_cout }}" required>
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
                    <input type="checkbox" class="daftar_hari" name="hari[]" value="{{ $key }}" {{ @in_array($key,@$data->hari)?'checked':'' }}>
                    <span style="position: relative;top: -2px;">{{ $h }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>
          <div class="form-group">
            <label for="early">User</label>
            <select class="form-control" name="user" id="user">
              @foreach (["Tidak Ada",
              "Semua User",
              "Semua Pegawai",
              "Semua Siswa",
              "User Tertentu"
              ] as $key => $value)
                <option {{ @$data->to_user==$key?"selected":"" }} value="{{ $key }}">{{ $value }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group" style="{{ @$data->to_user!=4?'display: none':'' }}" id="select-user">
            <label for="early">User Tertentu</label>
            <select class="form-control" style="width: 100%" name="users[]" id="users" multiple="multiple">
              <option></option>
              @if (@count(@$data->user)&&@$data->to_user==4)
                @foreach ($data->user as $key => $u)
                  <option selected value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
              @endif
            </select>
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
  $("#user").change(function(){
    if ($(this).val()==4) {
      $("#select-user").slideDown(200);
    }else{
      $("#select-user").slideUp(200);
    }
  })
  $("#users").select2({
    placeholder: "Ketik nama user ...",
    minimumInputLength: 3,
    ajax: {
      url: "{{ route('ajax.search.user') }}",
      dataType: 'json'
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
