<div class="dt-responsive">
  <table class="table table-bordered table-absen">
    <thead>
      @if (count($data)>1)
        <th>Nama</th>
      @endif
      <th>Jenis Gaji</th>
      <th>Besar Gaji</th>
      <th>Total Waktu</th>
      <th>Terlaksana</th>
      <th>Dibayarkan</th>
      <th>Total Gaji</th>
      <th>Tanda Tangan</th>
    </thead>
    <tbody>
      @foreach ($data as $key => $users)
        @php
          $totalgaji = 0;
          $rowspan = count($users['gaji']);
          foreach ($users['gaji'] as $k => $gaji) {
            $totalgaji += $gaji['total_gaji'];
          }
        @endphp
        <tr>
          @if (count($data)>1)
            <td style="text-align: left;padding: 15px 7px" rowspan="{{ $rowspan }}" class="font-weight-bold">{{ $users['name'] }}</td>
          @endif
          @foreach ($users['gaji'] as $key1 => $g)
            @php
              $gdone = [];
              array_push($gdone,$key1);
            @endphp
            <td style="text-align: left;padding: 15px 7px">{{ $g['name'] }}</td>
            <td style="text-align: left;padding: 15px 7px">{!! 'Rp '.number_format($g['gaji'],2,',','.').' / '.$g['menit'].' menit' !!}</td>
            <td style="padding: 15px 7px">{{ $g['total_waktu'].' menit' }}</td>
            <td style="padding: 15px 7px">{{ $g['terlaksana'].' menit' }}</td>
            <td style="text-align: left;padding: 15px 7px">{{ 'Rp '.number_format($g['total_gaji'],2,',','.') }}</td>
            @break(count($users['gaji'])>1)
          @endforeach
          <td style="text-align: left;padding: 15px 7px;text-align: center;font-size: 1.2em" rowspan="{{ $rowspan }}" class="currency font-weight-bold">{{ 'Rp '.number_format($totalgaji,2,',','.') }}</td>
          <td style="text-align: left;padding: 15px 7px" rowspan="{{ $rowspan }}"></td>
          @if (count($users['gaji'])>1)
            @foreach ($users['gaji'] as $key1 => $g)
              @continue(in_array($key1,$gdone))
              <tr>
                <td style="text-align: left;padding: 15px 7px">{{ $g['name'] }}</td>
                <td style="text-align: left;padding: 15px 7px">{!! 'Rp '.number_format($g['gaji'],2,',','.').' / '.$g['menit'].' menit' !!}</td>
                <td style="padding: 15px 7px">{{ $g['total_waktu'].' menit' }}</td>
                <td style="padding: 15px 7px">{{ $g['terlaksana'].' menit' }}</td>
                <td style="text-align: left;padding: 15px 7px">{{ 'Rp '.number_format($g['total_gaji'],2,',','.') }}</td>
              </tr>
            @endforeach
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
