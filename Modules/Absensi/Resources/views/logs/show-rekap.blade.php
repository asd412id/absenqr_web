@extends('absensi::logs.layouts.master-rekap')
@section('log-content')
  <div class="col-sm-12 text-center">
    @if (count($data))
      @include('absensi::logs.layouts.rekap-table')
    @else
      <div class="alert alert-info">
        <h5 class="m0 p0">Rekap Absen tidak tersedia!</h5>
      </div>
    @endif
  </div>
@endsection
