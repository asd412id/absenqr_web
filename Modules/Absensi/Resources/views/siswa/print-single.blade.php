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
      <h5 class="text-center font-weight-bold">DETAIL DATA SISWA</h5>
      <table class="table table-bordered mt-2">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA PRIBADI</td>
          <td rowspan="14" width="150" class="text-center">
            <img src="{{ $data->foto?asset('uploaded/'.$data->foto):url('assets/img/avatar.png') }}" alt="" class="img-fluid rounded d-inline" width="150">
          </td>
        </tr>
        <tr>
          <td width="200">NISN</td>
          <td width="1">:</td>
          <td>{{ $data->nisn??'-' }}</td>
        </tr>
        <tr>
          <td>NIS</td>
          <td>:</td>
          <td>{{ $data->nis??'-' }}</td>
        </tr>
        <tr>
          <td>Nama Lengkap</td>
          <td>:</td>
          <td>{{ $data->nama_lengkap??'-' }}</td>
        </tr>
        <tr>
          <td>Nama Panggilan</td>
          <td>:</td>
          <td>{{ $data->nama_panggilan??'-' }}</td>
        </tr>
        <tr>
          <td>Jenis Kelamin</td>
          <td>:</td>
          <td>{{ $data->jenis_kelamin===1?'Laki - Laki':'Perempuan' }}</td>
        </tr>
        <tr>
          <td>Tempat, Tanggal Lahir</td>
          <td>:</td>
          <td>{{ $data->tempat_lahir??'-' }}, {{ $data->tanggal_lahir?date('d-m-Y',strtotime($data->tanggal_lahir)):'-' }}</td>
        </tr>
        <tr>
          <td>Agama</td>
          <td>:</td>
          <td>{{ ucwords($data->agama)??'-' }}</td>
        </tr>
        <tr>
          <td>Kewarganegaraan</td>
          <td>:</td>
          <td>{{ $data->kewarganegaraan??'-' }}</td>
        </tr>
        <tr>
          <td>Jumlah Saudara Kandung</td>
          <td>:</td>
          <td>{{ $data->jumlah_saudara??'-' }} Orang</td>
        </tr>
        <tr>
          <td>Anak Ke</td>
          <td>:</td>
          <td>{{ $data->anak_ke??'-' }}</td>
        </tr>
        <tr>
          <td>Bahasa Sehari-hari</td>
          <td>:</td>
          <td>{{ $data->bahasa_hari??'-' }}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td>{{ $data->alamat??'-' }}</td>
        </tr>
        <tr>
          <td>No. Telepon</td>
          <td>:</td>
          <td>{{ $data->telp??'-' }}</td>
        </tr>
      </table>
      <table class="table table-bordered mt-2">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA SEKOLAH</td>
        </tr>
        <tr>
          <td width="165">Asal Sekolah</td>
          <td width="1">:</td>
          <td>{{ $data->asal_sekolah??'-' }}</td>
        </tr>
        <tr>
          <td>Tanggal diterima</td>
          <td>:</td>
          <td>{{ $data->tanggal_diterima?date('d-m-Y',strtotime($data->tanggal_diterima)):'-' }}</td>
        </tr>
        <tr>
          <td>Kelas</td>
          <td>:</td>
          <td>{{ $data->kelas??'-' }}</td>
        </tr>
        <tr>
          <td>Tanggal Tamat</td>
          <td>:</td>
          <td>{{ $data->tanggal_tamat?date('d-m-Y',strtotime($data->tanggal_tamat)):'-' }}</td>
        </tr>
        <tr>
          <td>Nomor Ijazah</td>
          <td>:</td>
          <td>{{ $data->nomor_ijazah??'-' }}</td>
        </tr>
        <tr>
          <td>Tanggal Ijazah</td>
          <td>:</td>
          <td>{{ $data->tanggal_ijazah?date('d-m-Y',strtotime($data->tanggal_ijazah)):'-' }}</td>
        </tr>
        <tr>
          <td>Pendidikan Selanjutnya</td>
          <td>:</td>
          <td>{{ $data->pendidikan_lanjut??'-' }}</td>
        </tr>
      </table>
      <div class="page-break"></div>
      <table class="table table-bordered mt-2" style="width: 49%;float: left">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA AYAH</td>
        </tr>
        <tr>
          <td width="95">Nama</td>
          <td width="1">:</td>
          <td>{{ $data->nama_ayah??'-' }}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td>{{ $data->alamat_ayah??'-' }}</td>
        </tr>
        <tr>
          <td>No. Telepon</td>
          <td>:</td>
          <td>{{ $data->telp_ayah??'-' }}</td>
        </tr>
        <tr>
          <td>Pekerjaan</td>
          <td>:</td>
          <td>{{ $data->pekerjaan_ayah??'-' }}</td>
        </tr>
      </table>
      <table class="table table-bordered mt-2" style="width: 49%;float: right">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA IBU</td>
        </tr>
        <tr>
          <td width="95">Nama</td>
          <td width="1">:</td>
          <td>{{ $data->nama_ibu??'-' }}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td>{{ $data->alamat_ibu??'-' }}</td>
        </tr>
        <tr>
          <td>No. Telepon</td>
          <td>:</td>
          <td>{{ $data->telp_ibu??'-' }}</td>
        </tr>
        <tr>
          <td>Pekerjaan</td>
          <td>:</td>
          <td>{{ $data->pekerjaan_ibu??'-' }}</td>
        </tr>
      </table>
      <table class="table table-bordered mt-2" style="width: 49%;float: left">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA WALI</td>
        </tr>
        <tr>
          <td width="95">Nama</td>
          <td width="1">:</td>
          <td>{{ $data->nama_wali??'-' }}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td>{{ $data->alamat_wali??'-' }}</td>
        </tr>
        <tr>
          <td>No. Telepon</td>
          <td>:</td>
          <td>{{ $data->telp_wali??'-' }}</td>
        </tr>
        <tr>
          <td>Pekerjaan</td>
          <td>:</td>
          <td>{{ $data->pekerjaan_wali??'-' }}</td>
        </tr>
      </table>
      <table class="table table-bordered mt-2" style="width: 49%;float: right">
        <tr>
          <td colspan="3" class="font-weight-bold">DATA KESEHATAN</td>
        </tr>
        <tr>
          <td width="135">Golongan Darah</td>
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
          <td>Kelainan Jasmani</td>
          <td>:</td>
          <td>{{ $data->kelainan_jasmani??'-' }}</td>
        </tr>
      </table>
      <div class="clearfix"></div>
      <table style="margin-top: 25px;float: right;page-break-inside: avoid !important">
        <tr>
          <td rowspan="5" width="150">
            {!! \QrCode::size('95')->generate('siswa - '.$data->uuid.'_'.time().' - by asd412id') !!}
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
