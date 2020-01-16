@extends('absensi::logs.layouts.master')
@section('log-content')
  <div class="col-sm-12 text-center">
    @if (count($data))
      @if (!request()->user)
        @include('absensi::logs.layouts.table')
      @else
        @include('absensi::logs.layouts.table-single')
      @endif
    @else
      <div class="alert alert-info">
        <h5 class="m0 p0">Log Absen tidak tersedia!</h5>
      </div>
    @endif
  </div>
@endsection
