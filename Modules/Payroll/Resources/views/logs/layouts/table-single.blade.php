@php
  $user = \App\User::find($data[0]['id']);
  $carbon = new \Carbon\Carbon;
  $tanggal = request()->start_date==request()->end_date?$carbon->createFromFormat('Y/m/d',request()->start_date)->locale('id')->translatedFormat('d F Y'):$carbon->createFromFormat('Y/m/d',request()->start_date)->locale('id')->translatedFormat('d F Y').' s.d. '.$carbon->createFromFormat('Y/m/d',request()->end_date)->locale('id')->translatedFormat('d F Y');
  $totalgaji = 0;
@endphp
<div class="wrapper text-left">
  <div class="slip-info-wrapper w-left">
    <table class="table slip-info">
      <tr>
        <th width="150">Nama</th>
        <th width="1">:</th>
        <td>{{ $user->name }}</td>
      </tr>
      <tr>
        <th>Jabatan</th>
        <th>:</th>
        <td>{{ $user->pegawai->jabatan }}</td>
      </tr>
    </table>
  </div>
  <div class="slip-info-wrapper w-right">
    <table class="table slip-info">
      <tr>
        <th width="150">Status</th>
        <th width="1">:</th>
        <td>{{ strtoupper($user->pegawai->status_kepegawaian) }}</td>
      </tr>
      <tr>
        <th>Periode Gaji</th>
        <th>:</th>
        <td>{{ $tanggal }}</td>
      </tr>
    </table>
  </div>
</div>
<div class="wrapper">
  <table class="table slip-detail">
    <thead>
      <th>Jenis Gaji</th>
      <th>Jumlah</th>
      <th>Beban Kerja</th>
      <th>Terlaksana (Menit)</th>
      <th>Diterima</th>
    </thead>
    <tbody>
      @foreach ($data[0]['gaji'] as $key => $gaji)
        @php
        $totalgaji += $gaji['total_gaji'];
        @endphp
        <tr>
          <td>{{ $gaji['name'] }}</td>
          <td>{{ 'Rp '.number_format($gaji['gaji'],2,',','.').' / '.$gaji['menit'].' menit' }}</td>
          <td>{{ $gaji['total_waktu'].' menit' }}</td>
          <td>{{ $gaji['terlaksana'].' menit' }}</td>
          <td>{{ 'Rp '.number_format($gaji['total_gaji'],2,',','.') }}</td>
        </tr>
      @endforeach
      <tr style="font-size: 1.2em">
        <td class="font-weight-bold" colspan="4" class="text-center">TOTAL DITERIMA</td>
        <td class="font-weight-bold">{{ 'Rp '.number_format($totalgaji,2,',','.') }}</td>
      </tr>
    </tbody>
  </table>
</div>
