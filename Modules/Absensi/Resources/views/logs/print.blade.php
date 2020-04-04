<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor') }}/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor') }}/bootstrap/dist/css/bootstrap.min.css">
    <style media="screen">
      html,body{
        width: 100%;height: 100%;margin: 0;padding: 0;
        /* font-size: 9pt !important; */
        font-family: Arial !important;
      }
      .page{
        padding: 0 20px;
      }
      .page-break{
        page-break-before: always;
      }
      .table{
        width: 100%;
        border: solid 1px #000;
        border-collapse: collapse;
        break-inside: auto;
      }
      .table th{
        text-align: center;
        vertical-align: middle !important;
      }
      .table th, .table td{
        border: solid 1px #000 !important;
        border-bottom: solid 1px #000;
        border-collapse: collapse;
      }
      .table td{
        text-align: center;
        vertical-align: top;
      }
      .text-center{
        text-align: center !important;
      }
      .nowrap{
        white-space: nowrap !important;
      }
      tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
        break-inside: avoid-page !important;
        page-break-before: auto !important;
        page-break-after: auto !important;
      }
      .table{
        font-size: 0.95em;
      }
      .table th{
        text-transform: uppercase;
      }
      .table th, .table td{
        padding: 3px 7px;
      }
      .desc{
        max-width: 225px;
      }
      .table .badge-dark{
        margin-bottom: 3px;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      @if (count($data))
        @php
          $carbon = new \Carbon\Carbon;
          $tanggal = request()->start_date==request()->end_date?$carbon->createFromFormat('Y/m/d',request()->start_date)->locale('id')->translatedFormat('l, d F Y'):$carbon->createFromFormat('Y/m/d',request()->start_date)->locale('id')->translatedFormat('d F Y').' s.d. '.$carbon->createFromFormat('Y/m/d',request()->end_date)->locale('id')->translatedFormat('d F Y');
          $qr = 'Absensi Logs - '.time().' - by asd412id';
        @endphp
        @if (!request()->user||count($users)>1)
          <h3 class="text-center">{{ request()->title??'Rekapitulasi Absensi' }}</h3>
          <p class="text-center">{{ $tanggal }}</p>
          @include('absensi::logs.layouts.table')
        @else
          <h3 class="text-center">{{ request()->title??'Rekapitulasi Absensi' }}</h3>
          <h5 class="text-center" style="margin-bottom: 0;padding-bottom: 0">{{ \App\User::where('id',request()->user)->first()->name }}</h5>
          <p class="text-center">{{ $tanggal }}</p>
          @include('absensi::logs.layouts.table-single')
        @endif
      @endif
      @include('layouts.ttd')
    </div>
  </body>
</html>
