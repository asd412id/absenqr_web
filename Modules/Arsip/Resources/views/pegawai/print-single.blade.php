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
        margin: 20px;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      <h3 class="text-center font-weight-bold">DETAIL DATA PEGAWAI</h3>
      <table class="table table-bordered mt-2">
        <tr>
          <td colspan="3" style="font-weight:bold">DATA PRIBADI</td>
          <td rowspan="16" width="150" class="text-center" style="vertical-align: top">
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
          <td>{{ $data->jenis_kelamin==1?'Laki - Laki':'Perempuan' }}</td>
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
          <td>NUPTK</td>
          <td>:</td>
          <td>{{ $data->nuptk??'-' }}</td>
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
      <table class="table table-bordered" style="margin-top: 15px">
        <tr>
          <td colspan="3" style="font-weight: bold">DATA KESEHATAN</td>
        </tr>
        <tr>
          <td width="200">Golongan Darah</td>
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
      <table class="table table-bordered" style="margin-top: 15px">
        <thead>
          <tr>
            <td colspan="4" style="border-bottom: none;font-weight: bold">RIWAYAT PENDIDIKAN</td>
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
      @include('layouts.ttd')
    </div>
  </body>
</html>
