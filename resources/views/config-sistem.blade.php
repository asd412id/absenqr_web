@extends('layouts.master')
@section('title',$title)
@section('header')
  <link rel="stylesheet" href="{{ asset('assets/vendor/datetimepicker/css/datetimepicker.min.css') }}">
@endsection
@section('head_icon')
  <i class="ik ik-settings bg-red"></i>
@endsection
@section('head_title',$title)
@section('head_desc','Pengaturan Informasi Sistem')
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">Pengaturan Sistem</li>
@endsection

@section('content')
  @include('configs.umum')
@endsection
@section('footer')
<script type="text/javascript">
  $("#rnd-act-key").change(function(){
    let actWrap = $("#act-wrap");
    actWrap.toggleClass('d-none');
    if (actWrap.is(":visible")) {
      actWrap.find("#act-key").prop('disabled',false);
    }else{
      actWrap.find("#act-key").prop('disabled',true);
    }
  })
  $("#act-key").on("change keyup keydown",function(e){
    var ev = e.which || e.keyCode;
    if ($(this).val().length>=6 && (ev!=8&&ev!=9&&ev!=13&&ev!=16&&ev!=18&&ev!=27)) {
      return false;
    }
  })

  @if (session()->has('message'))
    showSuccessToast('{{ session()->get('message') }}')
  @elseif ($errors->any())
    @foreach ($errors->all() as $key => $err)
      showDangerToast('{{ $err }}')
    @endforeach
  @endif
</script>
@endsection
