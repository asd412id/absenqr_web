@php
  $configs = \App\Configs::getAll();
  $percent = (int)str_replace('%','',request()->font_size??'100%');
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{!! $title !!}</title>
    <style>
      html,body{
        width: 100%;height: 100%;margin: 0;padding: 0;
        font-size: 13px;
        font-family: Arial !important;
      }
      @if (@$configs->template!='none')
        @if (@$configs->template=='atas')
        img.img-logo{
          width: {{ (45*$percent/100)."px !important" }}
        }
        @elseif (@$configs->template=='samping')
        img.img-logo{
          height: {{ (75*$percent/100)."px !important" }}
        }
        @endif
      @endif
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
      <h3 class="text-center font-weight-bold">DETAIL DATA SISWA</h3>
      <table class="table table-bordered mt-2">
        <tr>
          <td colspan="3" style="font-weight: bold">DATA PRIBADI</td>
          <td rowspan="14" width="150" class="text-center" style="vertical-align: top">
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
          <td>{{ $data->tempat_lahir??'-' }}, {{ $data->tanggal_lahir??'-' }}</td>
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
      <table class="table table-bordered" style="margin-top: 10px;">
        <tr>
          <td colspan="3" style="font-weight: bold">DATA SEKOLAH</td>
        </tr>
        <tr>
          <td width="200">Asal Sekolah</td>
          <td width="1">:</td>
          <td>{{ $data->asal_sekolah??'-' }}</td>
        </tr>
        <tr>
          <td>Tanggal diterima</td>
          <td>:</td>
          <td>{{ $data->tanggal_diterima??'-' }}</td>
        </tr>
        <tr>
          <td>Kelas</td>
          <td>:</td>
          <td>{{ $data->kelas??'-' }}</td>
        </tr>
        <tr>
          <td>Tanggal Tamat</td>
          <td>:</td>
          <td>{{ $data->tanggal_tamat??'-' }}</td>
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
      <table style="width: 100%" cellspacing="0" cellpadding="0">
        <tr>
          <td style="padding: 0">
            <table class="table table-bordered" style="width: 100%;float: left;margin-top: 10px">
              <tr>
                <td colspan="3" style="font-weight: bold">DATA AYAH</td>
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
          </td>
          <td style="padding: 0">
            <table class="table table-bordered" style="width: 100%;float: right;margin-top: 10px">
              <tr>
                <td colspan="3" style="font-weight: bold">DATA IBU</td>
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
          </td>
          <td style="padding: 0">
            <table class="table table-bordered" style="width: 100%;float: left;margin-top: 10px">
              <tr>
                <td colspan="3" style="font-weight: bold">DATA WALI</td>
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
          </td>
        </tr>
      </table>
      <table class="table table-bordered" style="width: 100%;float: right;margin-top: 10px">
        <tr>
          <td colspan="3" style="font-weight: bold">DATA KESEHATAN</td>
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
      @include('layouts.ttd')
    </div>
  </body>
</html>
