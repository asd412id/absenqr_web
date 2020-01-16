@extends('layouts.master')
@section('title',$title)
@section('header')
  <style media="screen">
    .table td:nth-child(8){
      white-space: normal !important;
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
        <div class="card-body">
          <table class="table table-hover table-striped nowrap" id="table-absensi-edit-jadwal-user">
            <thead>
              <th width="10">#</th>
              <th>Nama Jadwal</th>
              <th>Ruang</th>
              <th>Masuk</th>
              <th>Keluar</th>
              <th>Terlambat (Menit)</th>
              <th>Pulang Cepat (Menit)</th>
              <th>Hari</th>
              <th width="10">
                <label class="checkbox-inline">
                  <input type="checkbox" id="select-all">
                  Pilih
                </label>
              </th>
            </thead>
            <tbody>
              @foreach ($jadwal as $key => $v)
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td>{{ $v->nama_jadwal }}</td>
                  <td>{{ $v->get_ruang->nama_ruang }}</td>
                  <td>{{ $v->cin }}</td>
                  <td>{{ $v->cout }}</td>
                  <td>{{ $v->late }}</td>
                  <td>{{ $v->early }}</td>
                  <td>{{ implode(", ",$v->nama_hari) }}</td>
                  <td>
                    <input type="checkbox" class="check_jadwal" name="jadwal_user[]" value="{{ $v->id }}" {{ in_array($v->id,$data->jadwal()->select('id')->get()->pluck('id')->toArray()) ?'checked':''}}>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-center">
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
<script src="{{ url('assets/vendor/jquery.datetimepicker/jquery.datetimepicker.full.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  function checkBox() {
    $(".check_jadwal").each(function(i,v){
      if (!$(v).is(":checked")) {
        $("#select-all").prop('checked',false);
        return false;
      }
      $("#select-all").prop('checked',true);
    })
  }
  $(".check_jadwal").change(function(){
    checkBox();
  })
  $("#select-all").change(function(){
    if ($(this).is(":checked")) {
      $(".check_jadwal").prop('checked',true);
    }else{
      $(".check_jadwal").prop('checked',false);
    }
  })

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
