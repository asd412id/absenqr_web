<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
      html,body{
        width: 100%;height: 100%;margin: 0;padding: 0;
        font-size: 13.5px;
        font-family: Arial !important;
      }
      .page{
        padding: 20px;
      }
      .page-break{
        page-break-before: always;
      }
      .table{
        width: 100%;
        margin: 0 auto;
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
        border-bottom: solid 1px #000 !important;
        border-collapse: collapse !important;
      }
      .table-absen th, .table-absen td{
        font-size: {{ request()->font_size??'100%' }} !important;
      }
      .table td{
        vertical-align: top;
      }
      .text-center{
        text-align: center !important;
      }
      .text-left{
        text-align: left !important;
      }
      .nowrap{
        white-space: nowrap !important;
      }
      tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
        break-inside: avoid-page !important;
        page-break-before: auto !important;
        page-break-after: auto !important;
        vertical-align: middle !important;
      }
      .table th{
        text-transform: uppercase;
      }
      .table th, .table td{
        padding: 3px 7px;
        vertical-align: middle !important;
      }
      .desc{
        max-width: 225px;
      }
      .table .badge-dark{
        margin-bottom: 3px;
      }
      span.badge{
        display: block !important;
        padding: 2px 4px !important;
        margin: 1px !important;
        border-radius: 5px !important;
      }
      .badge-dark{
        background-color: rgba(191,128,255,.3) !important;
      }
      .bg-primary,.badge-primary{
        background-color: rgba(0,153,255,.3) !important;
      }
      .bg-warning,.badge-warning{
        background-color: rgba(255,255,0,.3) !important;
      }
      .bg-success,.badge-success{
        background-color: rgba(0,255,0,.3) !important;
      }
      .bg-danger,.badge-danger{
        background-color: rgba(255,0,0,.3) !important;
      }
      @page{
        margin: 10px 20px;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      <h3 class="text-center font-weight-bold">DAFTAR SISWA</h3>
      <table class="table table-bordered table-striped mt-3">
        <thead>
          <tr>
            <th>NISN</th>
            <th>NIS</th>
            <th>NAMA LENGKAP</th>
            <th>TEMPAT, TANGGAL LAHIR</th>
            <th>JENIS KELAMIN</th>
            <th>ASAL SEKOLAH</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $key => $v)
            <tr>
              <td>{{ $v->nisn }}</td>
              <td>{{ $v->nis }}</td>
              <td>{{ $v->nama_lengkap }}</td>
              <td>{{ $v->tempat_lahir??'-' }}, {{ $v->tanggal_lahir??'-' }}</td>
              <td>{{ $v->jenis_kelamin==1?'Laki - Laki':'Perempuan' }}</td>
              <td>{{ $v->asal_sekolah }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      @include('layouts.ttd',['qr'=>'daftar siswa - '.time().' - by asd412id'])
    </div>
  </body>
</html>
