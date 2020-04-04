@extends('layouts.master')
@section('title',$title)

@section('head_icon')
  <i class="fas fa-money-bill-wave bg-success"></i>
@endsection
@section('head_title',$title)
@section('head_desc',$subtitle)
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('payroll.user.index') }}">{{ $title }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $subtitle }}</li>
@endsection

@section('content')
<form action="{{ route('payroll.user.update',['uuid'=>$data->uuid]) }}" enctype="multipart/form-data" method="post">
  @csrf
  <div class="row">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>{{ $data->user->name }}</h3>
        </div>
        <div class="card-body">
          <input type="hidden" name="user" id="user" value="{{ $data->user->id }}">
          <div class="form-group">
            <label for="time">Nama Gaji</label>
            <input type="text" name="name" class="form-control" value="{{ old('name')??$data->name }}" placeholder="Haji Honor">
          </div>
          <div class="form-group">
            <label for="time">Jumlah Gaji</label>
            <input type="text" name="gaji" class="form-control currency" data-a-dec="," data-a-sep="." data-a-sign="Rp " value="{{ old('gaji')??$data->gaji }}" placeholder="Rp 0,00">
          </div>
          <div class="form-group">
            <label for="time">Per Menit</label>
            <input type="number" name="menit" class="form-control" value="{{ old('menit')??$data->menit }}">
          </div>
          <div class="form-group">
            <label for="time">Hitung Lembur?</label>
            <select class="form-control" name="lembur">
              <option {{ $data->lembur=='N'?'selected':'' }} value="N">Tidak</option>
              <option {{ $data->lembur=='Y'?'selected':'' }} value="Y">Ya</option>
            </select>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-save"></i> SIMPAN</button>
            <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa fa-fw fa-undo"></i> KEMBALI</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3>Pilih Jadwal</h3>
        </div>
        <div class="card-body">
          <div class="card" style="box-shadow: none">
              <div class="card-body p0">
                <table class="table table-hover table-striped table-bordered nowrap" id="table-jadwal">
                  <thead>
                    <th width="10">#</th>
                    <th>Nama Jadwal</th>
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
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('footer')
<script type="text/javascript">
  function getJadwal(user) {
    $("#table-jadwal tbody").html('<tr><td colspan="5" class="text-center">Memuat jadwal ...</td></tr>');
    $.get(location.href,{
      user: user
    },function(res){
      let content;
      if (res.length == 0) {
        content = '<tr><td colspan="5" class="text-center">Jadwal tidak tersedia.</td></tr>';
      }else{
        let jd = {!! json_encode($data->jadwal_id) !!};
        res.forEach(function(v,i){
          let selected = jd?jd.indexOf(v.id.toString()):-1;
          content += '<tr>';
          content += '<td>'+(i+1)+'</td>';
          content += '<td>'+v.nama_jadwal+'</td>';
          content += '<td>'+v.get_ruang.nama_ruang+'</td>';
          content += '<td><input type="checkbox" name="jadwal[]" class="daftar_jadwal" value="'+v.id+'" '+(selected>=0?'checked':'')+' /></td>';
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
    getJadwal(user);
  }

  function checkBox() {
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
