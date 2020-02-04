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
      table tr{
        page-break-inside: avoid !important;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      <h5 class="text-center font-weight-bold">DAFTAR SISWA</h5>
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
