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
        font-size: 9pt !important;
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
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      @if (count($data))
        @php
          $carbon = new \Carbon\Carbon;
          $tanggal = request()->start_date==request()->end_date?$carbon->createFromFormat('Y/m/d',request()->start_date)->translatedFormat('d/m/Y'):$carbon->createFromFormat('Y/m/d',request()->start_date)->format('d/m/Y').' - '.$carbon->createFromFormat('Y/m/d',request()->end_date)->format('d/m/Y')
        @endphp
        @if (!request()->user)
          <h3 class="text-center">Rekapitulasi Absensi</h3>
          <p class="text-center">Tanggal: {{ $tanggal }}</p>
          @include('absensi::logs.layouts.table')
        @else
          <h3 class="text-center">Rekapitulasi Absensi</h3>
          <p class="text-center" style="margin-bottom: 0;padding-bottom: 0">Nama: {{ \App\User::where('uuid',request()->user)->first()->name }}</p>
          <p class="text-center">Tanggal: {{ $tanggal }}</p>
          @include('absensi::logs.layouts.table-single')
        @endif
      @endif
      <div class="clearfix"></div>
      <table class="" style="margin-top: 25px;float: right;page-break-inside: avoid !important">
        <tr>
          <td rowspan="5" width="150">
            {!! \QrCode::size('95')->generate('absen log - _'.time().' - by asd412id') !!}
          </td>
          <td style="height: 30px">Sinjai, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
          <td>Mengetahui,</td>
        </tr>
        <tr>
          <td class="font-weight-bold">Kepala UPTD SMP Negeri 39 Sinjai</td>
        </tr>
        <tr>
          <td style="height: 75px"></td>
        </tr>
        <tr>
          <td style="line-height: 1em">
            <span class="font-weight-bold">SITTI SAIDAH SUYUTI, S.Pd.,M.Pd.</span><br>
            NIP. 19710626 199702 2 005
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>
