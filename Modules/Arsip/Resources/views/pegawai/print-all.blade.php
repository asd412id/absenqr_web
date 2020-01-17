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
      table tr{
        page-break-inside: avoid !important;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      <h5 class="text-center font-weight-bold">DAFTAR PEGAWAI</h5>
      <table class="table table-bordered table-striped mt-3">
        <thead>
          <th>NIP</th>
          <th>NAMA</th>
          <th>TEMPAT, TANGGAL LAHIR</th>
          <th>JENIS KELAMIN</th>
          <th>STATUS KEPEGAWAIAN</th>
          <th>JABATAN</th>
        </thead>
        <tbody>
          @foreach ($data as $key => $v)
            <tr>
              <td>{{ $v->nip }}</td>
              <td>{{ $v->nama }}</td>
              <td>{{ $v->tempat_lahir??'-' }}, {{ $v->tanggal_lahir??'-' }}</td>
              <td>{{ $v->jenis_kelamin==1?'Laki - Laki':'Perempuan' }}</td>
              <td>{{ strtoupper($v->status_kepegawaian) }}</td>
              <td>{{ $v->jabatan }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="clearfix"></div>
      <table style="margin-top: 25px;float: right;page-break-inside: avoid !important">
        <tr>
          <td rowspan="5" width="150">
            {!! \QrCode::size('95')->generate('daftar pegawai - '.time().' - by asd412id') !!}
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
