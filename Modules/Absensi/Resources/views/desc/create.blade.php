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
  <i class="fas fa-edit bg-info"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Tambah Keterangan Absensi Baru')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('absensi.desc.index') }}">{{ 'Keterangan Absensi' }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('content')
<form action="{{ route('absensi.desc.store') }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Keterangan Baru</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="ruang">User</label>
            <select class="form-control select2" data-url="{{ route('ajax.search.user') }}" data-placeholder="Pilih user" name="user" id="user" required>
            </select>
          </div>
          <div class="form-group">
            <label for="time">Tanggal</label>
            <input type="text" name="time" class="form-control datepicker" id="time" value="{{ old('time')??date('d-m-Y') }}" required placeholder="Tanggal">
          </div>
          <div class="form-group">
            <label for="time">Keterangan</label>
            <textarea name="desc" rows="8" class="form-control" placeholder="Keterangan" required>{{ old('desc') }}</textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Pilih Jadwal</h3>
        </div>
        <div class="card-body pb-0">
          <div class="card" style="box-shadow: none">
              <div class="card-body p0">
                <table class="table table-hover table-striped table-bordered nowrap" id="table-jadwal">
                  <thead>
                    <th width="10">#</th>
                    <th>Nama Jadwal</th>
                    <th>Nama Alias</th>
                    <th>Ruang</th>
                    <th width="10" style="white-space: nowrap">
                      <label class="checkbox-inline m0">
                        <input type="checkbox" id="select-all">
                        <span>Pilih</span>
                      </label>
                    </th>
                  </thead>
                  <tbody></tbody>
                </table>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
                  <a href="{{ route('absensi.desc.index') }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
                </div>
              </div>
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
  function getJadwal(user,date) {
    $("#table-jadwal tbody").html('<tr><td colspan="5" class="text-center">Memuat jadwal ...</td></tr>');
    $.get('{{ route('absensi.desc.create') }}',{
      user: user,
      date: date
    },function(res){
      let content;
      if (res.length == 0) {
        content = '<tr><td colspan="5" class="text-center">Jadwal tidak tersedia.</td></tr>';
      }else{
        res.forEach(function(v,i){
          content += '<tr>';
          content += '<td>'+(i+1)+'</td>';
          content += '<td>'+v.nama_jadwal+'</td>';
          content += '<td>'+(v.alias??'-')+'</td>';
          content += '<td>'+v.get_ruang.nama_ruang+'</td>';
          content += '<td><input type="checkbox" name="jadwal[]" class="daftar_jadwal" value="'+v.id+'" /></td>';
          content += '</tr>';
        });
      }
      $("#table-jadwal tbody").html(content);
      initProcess();
      checkBox();
    },'json')
  }

  function initJadwal() {
    let user = $("#user").val();
    let time = $("#time").val();
    getJadwal(user,time);
  }

  function checkBox() {
    if ($(".daftar_jadwal:checked").length>0) {
      $("button[type=submit]").prop('disabled',false);
    }else{
      $("button[type=submit]").prop('disabled',true);
    }

    $(".daftar_jadwal").each(function(i,v){
      if (!$(v).is(":checked")) {
        $("#select-all").prop('checked',false);
        return false;
      }
      $("#select-all").prop('checked',true);
    })

    $(".daftar_jadwal").each(function(i,e){
      let target = $(e).data('target');
      let v = $(e).data('id');
      if ($(e).is(":checked")) {
        let w = '<input type="hidden" name="jadwal[]" value="'+v+'" id="'+target+'" />';
        let isSet = $("#jadwal-wrapper").find("#"+target).length;
        if (isSet == 0) {
          $("#jadwal-wrapper").append(w);
        }
      }else{
        $("#jadwal-wrapper").find("#"+target).remove();
      }
    })

  }
  function initProcess() {
    $(".daftar_jadwal").on('change',function(){
      checkBox();
    })
    $("#select-all").change(function(){
      if ($(this).is(":checked")) {
        $(".daftar_jadwal").prop('checked',true).change();
      }else{
        $(".daftar_jadwal").prop('checked',false).change();
      }
    })
  }

  initProcess();
  checkBox();

  $("#user,#time").change(function(){
    $(this).blur();
    initJadwal();
  })

  initJadwal();

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
