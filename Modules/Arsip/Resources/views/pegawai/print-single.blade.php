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
      tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
        break-inside: avoid-page !important;
        page-break-before: avoid !important;
        page-break-after: avoid !important;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      <h5 class="text-center font-weight-bold">DETAIL DATA PEGAWAI</h5>
      <table class="table table-bordered mt-2">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA PRIBADI</td>
          <td rowspan="15" width="150" class="text-center">
            <img src="{{ $data->foto?asset('uploaded/'.$data->foto):url('assets/img/avatar.png') }}" alt="" class="img-fluid rounded d-inline" width="150">
          </td>
        </tr>
        <tr>
          <td width="200">Nama Lengkap</td>
          <td width="1">:</td>
          <td>{{ $data->nama??'-' }}</td>
        </tr>
        <tr>
          <td>Tempat, Tanggal Lahir</td>
          <td>:</td>
          <td>{{ $data->tempat_lahir??'-' }}, {{ $data->tanggal_lahir??'-' }}</td>
        </tr>
        <tr>
          <td>Jenis Kelamin</td>
          <td>:</td>
          <td>{{ $data->jenis_kelamin===1?'Laki - Laki':'Perempuan' }}</td>
        </tr>
        <tr>
          <td>Agama</td>
          <td>:</td>
          <td>{{ ucwords($data->agama)??'-' }}</td>
        </tr>
        <tr>
          <td>Status Kawin</td>
          <td>:</td>
          <td>{{ ucwords($data->status_kawin)??'-' }}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td>{{ $data->alamat??'-' }}</td>
        </tr>
        <tr>
          <td>Nomor Telepon</td>
          <td>:</td>
          <td>{{ $data->telp??'-' }}</td>
        </tr>
        <tr>
          <td>Pendidikan Terakhir</td>
          <td>:</td>
          <td>{{ $data->pendidikan_akhir??'-' }}</td>
        </tr>
        <tr>
          <td>Jabatan</td>
          <td>:</td>
          <td>{{ $data->jabatan??'-' }}</td>
        </tr>
        <tr>
          <td>Status Kepegawaian</td>
          <td>:</td>
          <td>{{ strtoupper($data->status_kepegawaian)??'-' }}</td>
        </tr>
        <tr>
          <td>NIP</td>
          <td>:</td>
          <td>{{ $data->nip??'-' }}</td>
        </tr>
        <tr>
          <td>Unit Kerja</td>
          <td>:</td>
          <td>{{ $data->unit_kerja??'-' }}</td>
        </tr>
        <tr>
          <td>Mulai Masuk</td>
          <td>:</td>
          <td>{{ $data->mulai_masuk??'-' }}</td>
        </tr>
        <tr>
          <td>Kegemaran/Hobi</td>
          <td>:</td>
          <td>{{ $data->kegemaran??'-' }}</td>
        </tr>
      </table>
      <table class="table table-bordered mt-2">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA KESEHATAN</td>
        </tr>
        <tr>
          <td width="125">Golongan Darah</td>
          <td width="1">:</td>
          <td>{{ $data->golda??'-' }}</td>
        </tr>
        <tr>
          <td>Tinggi Badan</td>
          <td>:</td>
          <td>{{ $data->tinggi??'-' }} cm</td>
        </tr>
        <tr>
          <td>Berat Badan</td>
          <td>:</td>
          <td>{{ $data->berat??'-' }} Kg</td>
        </tr>
        <tr>
          <td>Rambut</td>
          <td>:</td>
          <td>{{ $data->rambut??'-' }}</td>
        </tr>
        <tr>
          <td>Bentuk Muka</td>
          <td>:</td>
          <td>{{ $data->bentuk_muka??'-' }}</td>
        </tr>
        <tr>
          <td>Warna Muka</td>
          <td>:</td>
          <td>{{ $data->warna_muka??'-' }}</td>
        </tr>
        <tr>
          <td>Ciri - Ciri Khas</td>
          <td>:</td>
          <td>{{ $data->ciri_ciri??'-' }}</td>
        </tr>
      </table>
      <div class="page-break"></div>
      <table class="table table-bordered mt-2">
        <thead>
          <tr>
            <td colspan="4" class="font-weight-bold" style="border-bottom: none">RIWAYAT PENDIDIKAN</td>
          </tr>
          <tr>
            <th width="1" class="text-center">No</th>
            <th>Nama Lembaga</th>
            <th>Status Lembaga</th>
            <th>Tahun Ijazah</th>
          </tr>
        </thead>
        <tbody>
          @php
          $riwayat_pendidikan = json_decode($data->riwayat_pendidikan);
          @endphp
          @if (!count($riwayat_pendidikan))
            <tr>
              <td colspan="4" class="text-center">Riwayat pendidikan tidak tersedia</td>
            </tr>
          @else
            @foreach ($riwayat_pendidikan as $key => $v)
              <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td>{{ $v->nama_pendidikan }}</td>
                <td>{{ ucwords($v->status_pendidikan) }}</td>
                <td>{{ $v->tahun_ijazah }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
      <div class="clearfix"></div>
      <table style="margin-top: 25px;float: right;page-break-inside: avoid !important">
        <tr>
          <td rowspan="5" width="150">
            {!! \QrCode::size('95')->generate('pegawai - '.$data->uuid.'_'.time().' - by asd412id') !!}
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
