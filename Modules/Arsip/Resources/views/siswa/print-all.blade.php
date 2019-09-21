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
    </style>
  </head>
  <body>
    <div class="page">
      @include('arsip::layouts.kop')
      <h5 class="text-center font-weight-bold">DAFTAR SISWA</h5>
      <table class="table table-bordered table-striped mt-3">
        <thead>
          <th>NISN</th>
          <th>NIS</th>
          <th>NAMA LENGKAP</th>
          <th>TEMPAT, TANGGAL LAHIR</th>
          <th>JENIS KELAMIN</th>
          <th>ASAL SEKOLAH</th>
        </thead>
        <tbody>
          @foreach ($data as $key => $v)
            <tr>
              <td>{{ $v->nisn }}</td>
              <td>{{ $v->nis }}</td>
              <td>{{ $v->nama_lengkap }}</td>
              <td>{{ $v->tempat_lahir??'-' }}, {{ $v->tanggal_lahir?date('d-m-Y',strtotime($v->tanggal_lahir)):'-' }}</td>
              <td>{{ $v->jenis_kelamin==1?'Laki - Laki':'Perempuan' }}</td>
              <td>{{ $v->asal_sekolah }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="clearfix"></div>
      <table style="margin-top: 45px;float: right;page-break-inside: avoid !important">
        <tr>
          <td rowspan="5" width="150">
            {!! \QrCode::size('95')->generate('daftar siswa - '.time().' - by asd412id') !!}
          </td>
          <td style="height: 30px">Sinjai, {{ date('d/m/Y') }}</td>
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
