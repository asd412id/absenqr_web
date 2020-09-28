<table style="margin: 0 auto">
  <tr>
    <td style="vertical-align: bottom !important;text-align: center;padding: 0 5px">
      @if (@$configs->logo1)
      <img class="img-logo" src="{{ @$configs->logo1?asset('uploaded/'.@$configs->logo1):url('assets/img/sinjai.png') }}" alt="" style="display: inline">
      @endif
    </td>
    <td align="center">
      <div style="display: inline-block;word-break: break-all">
        <h4 class="font-weight-bold" style="text-align: center;margin: 0;padding: 0">{!! nl2br(@$configs->kop??"PEMERINTAH KABUPATEN SINJAI\nDINAS PENDIDIKAN") !!}</h4>
        <h2 class="font-weight-bold" style="text-align: center;margin: 0;padding: 0;text-transform: uppercase !important">{{ @$configs->nama_instansi??'UPTD SMP NEGERI 39 SINJAI' }}</h2>
        <p style="text-align: center;margin: 0;padding: 0;font-size: 0.9em"><em>{!! nl2br(@$configs->alamat) !!}</em></p>
      </div>
    </td>
    <td style="vertical-align: bottom !important;text-align: center;padding: 0 5px">
      @if (@$configs->logo2)
        <img class="img-logo" src="{{ @$configs->logo2?asset('uploaded/'.@$configs->logo2):url('assets/img/sinjai.png') }}" alt="" style="display: inline">
      @endif
    </td>
  </tr>
</table>
<div style="border-top: solid 3px #000;border-bottom: solid 1px #000;margin-top: 3px;margin-bottom: 15px;padding: 1px 0;clear: both; float: none"></div>
