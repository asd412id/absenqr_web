@php
  $configs = \App\Configs::getAll();
@endphp
<table style="width: 100%">
  <tr>
    <td style="width: 60%;white-space: nowrap">
      @if (strpos(Request::url(),route('absensi.log.index'))!==false)
        <table class="" style="margin-top: 25px;float: left;page-break-inside: avoid !important;white-space: nowrap;">
          <tr>
            <td class="font-weight-bold" colspan="2">Keterangan Warna Absen:</td>
          </tr>
          <tr>
            <td style="display: inline-block;margin: 1px;border: solid 1px #000"></td>
            <td class="status-color" style="padding-left: 10px">Kehadiran Tepat Waktu</td>
          </tr>
          <tr>
             <td class="status-color bg-warning" style="display: inline-block;margin: 1px;border: solid 1px #000"></td>
            <td style="padding-left: 10px">Terlambat / Pulang Cepat</td>
          </tr>
          <tr>
             <td class="status-color bg-success" style="display: inline-block;margin: 1px;border: solid 1px #000"></td>
            <td style="padding-left: 10px">Sakit / Izin / Keterangan</td>
          </tr>
          <tr>
             <td class="status-color bg-danger" style="display: inline-block;margin: 1px;border: solid 1px #000"></td>
            <td style="padding-left: 10px">Tidak Hadir</td>
          </tr>
        </table>
      @elseif (strpos(Request::url(),route('absensi.log.rekap'))!==false)
        <table style="width: 60%;margin-top: 25px;float: left;page-break-inside: avoid !important;white-space: nowrap;">
          <tr>
            <td style="font-weight: bold;padding-left: 0" colspan="2">Keterangan Absen:</td>
          </tr>
          <tr>
            <td class="status-color" style="display: inline-block;text-align: center;margin: 1px;border: solid 1px #000">
              &#10004;
            </td>
            <td style="padding-left: 10px">Hadir</td>
          </tr>
          <tr>
            <td class="status-color bg-warning" style="display: inline-block;text-align: center;margin: 1px;border: solid 1px #000"></td>
            <td style="padding-left: 10px">Sakit / Izin / Cuti</td>
          </tr>
        </table>
      @endif
    </td>
    <td style="width: 30%;white-space: nowrap !important">
      <table style="margin-top: 25px;float: right !important;page-break-inside: avoid !important;white-space: nowrap;">
        <tr>
          <td style="height: 30px;">{{ @$config->kota??'Sinjai' }}, {{ \Carbon\Carbon::parse(@request()->end_date)->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
          <td>Mengetahui,</td>
        </tr>
        <tr>
          <td>Kepala {{ @$configs->nama_instansi??'UPTD SMP Negeri 39 Sinjai' }}</td>
        </tr>
        <tr>
          <td style="height: 75px"></td>
        </tr>
        <tr>
          <td style="line-height: 1em;font-weight: bold">
            {!! @$configs->pimpinan?nl2br($configs->pimpinan):'SITTI SAIDAH SUYUTI, S.Pd.,M.Pd.<br>
            NIP. 19710626 199702 2 005' !!}
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
