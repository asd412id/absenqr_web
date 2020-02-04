<div style="text-align: center">
  <div style="max-width: 900px;margin: 0 auto">
    @if (@$configs->logo1)
      <div style="text-align: center;float: left">
        <img src="{{ @$configs->logo1?asset('uploaded/'.@$configs->logo1):url('assets/img/sinjai.png') }}" alt="" height="111" style="display: inline;margin-bottom: 10px">
      </div>
    @endif
    <div style="display: inline-block;word-break: break-all">
      <h4 class="font-weight-bold" style="text-align: center;margin: 0">{!! nl2br(@$configs->kop??"PEMERINTAH KABUPATEN SINJAI\nDINAS PENDIDIKAN") !!}</h4>
      <h4 class="font-weight-bold" style="text-align: center;margin: 0;text-transform: uppercase !important">{{ @$configs->nama_instansi??'UPTD SMP NEGERI 39 SINJAI' }}</h4>
      <p style="text-align: center;margin-bottom: 0;margin-top: 5px;font-size: 0.9em"><em>{!! nl2br(@$configs->alamat) !!}</em></p>
    </div>
    @if (@$configs->logo2)
      <div style="text-align: center;float: right">
        <img src="{{ @$configs->logo2?asset('uploaded/'.@$configs->logo2):url('assets/img/sinjai.png') }}" alt="" height="111" style="display: inline;margin-bottom: 10px">
      </div>
    @endif
  </div>
  <div style="border-top: solid 3px #000;border-bottom: solid 1px #000;margin-top: 3px;margin-bottom: 15px;padding: 1px 0;clear: both; float: none"></div>
</div>
