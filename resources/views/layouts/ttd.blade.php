@php
  $configs = \App\Configs::getAll();
@endphp
<div class="clearfix"></div>
<table class="" style="margin-top: 25px;float: right;page-break-inside: avoid !important;white-space: nowrap;">
  <tr>
    <td rowspan="5" width="150">
      {!! \QrCode::size('115')->generate(@$qr??'Aplikasi Sekolah - '.time().' - by asd412id') !!}
    </td>
    <td style="height: 30px">{{ @$config->kota??'Sinjai' }}, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td>
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
