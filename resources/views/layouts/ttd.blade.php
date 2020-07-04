@php
  $configs = \App\Configs::getAll();
@endphp
<div class="clearfix"></div>
<table class="" style="margin-top: 25px;float: left;page-break-inside: avoid !important;white-space: nowrap;">
  <tr>
    <td class="font-weight-bold" colspan="2">Keterangan Warna Absen:</td>
  </tr>
  <tr>
    <td>
      <span style="display: inline-block;margin: 1px;padding: 17px;border: solid 1px #000"></span>
    </td>
    <td style="padding-left: 10px">Kehadiran Tepat Waktu</td>
  </tr>
  <tr>
     <td>
      <span class="bg-warning" style="display: inline-block;margin: 1px;padding: 17px;border: solid 1px #000"></span>
    </td>
    <td style="padding-left: 10px">Terlambat / Pulang Cepat</td>
  </tr>
  <tr>
     <td>
      <span class="bg-success" style="display: inline-block;margin: 1px;padding: 17px;border: solid 1px #000"></span>
    </td>
    <td style="padding-left: 10px">Sakit / Izin / Keterangan</td>
  </tr>
  <tr>
     <td>
      <span class="bg-danger" style="display: inline-block;margin: 1px;padding: 17px;border: solid 1px #000"></span>
    </td>
    <td style="padding-left: 10px">Tidak Hadir</td>
  </tr>
</table>
<table class="" style="margin-top: 25px;float: right;page-break-inside: avoid !important;white-space: nowrap;">
  <tr>
    <td rowspan="5" width="150">
      {!! \QrCode::size('115')->generate(@$qr??'Aplikasi Sekolah - '.time().' - by asd412id') !!}
    </td>
    <td style="height: 30px">{{ @$config->kota??'Sinjai' }}, {{ \Carbon\Carbon::parse(@request()->end_date)->locale('id')->translatedFormat('d F Y') }}</td>
  </tr>
  <tr>
    <td>Mengetahui,</td>
  </tr>
  <tr>
    <td class="font-weight-bold">Kepala {{ @$configs->nama_instansi??'UPTD SMP Negeri 39 Sinjai' }}</td>
  </tr>
  <tr>
    <td style="height: 75px"></td>
  </tr>
  <tr>
    <td style="line-height: 1em" class="font-weight-bold">
      {!! @$configs->pimpinan?nl2br($configs->pimpinan):'SITTI SAIDAH SUYUTI, S.Pd.,M.Pd.<br>
      NIP. 19710626 199702 2 005' !!}
    </td>
  </tr>
</table>
