@extends('payroll::logs.layouts.master')
@section('log-content')
  <div class="col-sm-12 text-center">
    @if (count($data))
      @if (count($users)>1)
        @include('payroll::logs.layouts.table')
      @else
        @include('payroll::logs.layouts.table-single')
      @endif
    @else
      <div class="alert alert-info">
        <h5 class="m0 p0">Daftar gaji tidak tersedia!</h5>
      </div>
    @endif
  </div>
@endsection
