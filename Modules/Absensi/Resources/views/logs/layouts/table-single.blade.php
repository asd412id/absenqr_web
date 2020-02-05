<div class="dt-responsive">
  <table class="table table-bordered table-absen">
    <thead>
      <th>Tanggal</th>
      <th>Jadwal</th>
      <th>Check In</th>
      <th>Check Out</th>
      <th>Terlambat</th>
      <th>Pulang Cepat</th>
      <th style="white-space: nowrap">Jumlah Jam</th>
      <th>Keterangan</th>
    </thead>
    <tbody>
      @php
        $hari = [7=>'Ahad',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jum\'at',6=>'Sabtu'];
      @endphp
      @foreach ($data as $key => $user)
        @php
          $udone = [];
          $rowspan[$key] = 0;
          foreach ($user as $k => $d) {
            $rowspan[$key] += count($d);
          }
        @endphp
        <tr>
          @foreach ($user as $key1 => $day)
            @php
              array_push($udone,$key1);
              $ddone = [];
              $h = \Carbon\Carbon::createFromFormat('d/m/Y',$key1)->format('N');
            @endphp
            <td class="nowrap" style="vertical-align: top" rowspan="{{ count($day) }}">{{ $hari[$h].', '.$key1 }}</td>
            @foreach ($day as $key2 => $d)
              @php
                array_push($ddone,$key2)
              @endphp
              <td class="text-left"><span class="font-weight-bold badge badge-dark p0" style="font-size: 1em;padding: 3px 7px !important">{{ $d['jadwal']->nama_jadwal }}</span><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->cin.' - '.$d['jadwal']->cout }}</em></td>
              <td class="{{ $d['colorCin'] }}">{{ $d['acin'] }}</td>
              <td class="{{ $d['colorCout'] }}">{{ $d['acout'] }}</td>
              <td>{{ $d['alate'] }}</td>
              <td>{{ $d['aearly'] }}</td>
              <td>{!! $d['acount'] !!}</td>
              <td class="text-left desc">{!! nl2br($d['desc']) !!}</td>
              @break(count($day)>1)
            @endforeach
            @if (count($day)>1)
              @foreach ($day as $key2 => $d)
                @continue(in_array($key2,$ddone))
                <tr>
                  <td class="text-left"><span class="font-weight-bold badge badge-dark p0" style="font-size: 1em;padding: 3px 7px !important">{{ $d['jadwal']->nama_jadwal }}</span><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->cin.' - '.$d['jadwal']->cout }}</em></td>
                  <td class="{{ $d['colorCin'] }}">{{ $d['acin'] }}</td>
                  <td class="{{ $d['colorCout'] }}">{{ $d['acout'] }}</td>
                  <td>{{ $d['alate'] }}</td>
                  <td>{{ $d['aearly'] }}</td>
                  <td>{!! $d['acount'] !!}</td>
                  <td class="text-left desc">{!! nl2br($d['desc']) !!}</td>
                </tr>
              @endforeach
            @endif
            @break(count($user)>1)
          @endforeach
        </tr>
        @if (count($user)>1)
          @foreach ($user as $key1 => $day)
            @continue(in_array($key1,$udone))
            @php
              $h = \Carbon\Carbon::createFromFormat('d/m/Y',$key1)->format('N');
            @endphp
            <tr>
              <td class="nowrap" style="vertical-align: top" rowspan="{{ count($day) }}">{{ $hari[$h].', '.$key1 }}</td>
              @foreach ($day as $key2 => $d)
                @php
                array_push($ddone,$key2)
                @endphp
                <td class="text-left"><span class="font-weight-bold badge badge-dark p0" style="font-size: 1em;padding: 3px 7px !important">{{ $d['jadwal']->nama_jadwal }}</span><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->cin.' - '.$d['jadwal']->cout }}</em></td>
                <td class="{{ $d['colorCin'] }}">{{ $d['acin'] }}</td>
                <td class="{{ $d['colorCout'] }}">{{ $d['acout'] }}</td>
                <td>{{ $d['alate'] }}</td>
                <td>{{ $d['aearly'] }}</td>
                <td>{!! $d['acount'] !!}</td>
                <td class="text-left desc">{!! nl2br($d['desc']) !!}</td>
                @break(count($day)>1)
              @endforeach
            </tr>
            @if (count($day)>1)
              @foreach ($day as $key2 => $d)
                @continue(in_array($key2,$ddone))
                <tr>
                  <td class="text-left"><span class="font-weight-bold badge badge-dark p0" style="font-size: 1em;padding: 3px 7px !important">{{ $d['jadwal']->nama_jadwal }}</span><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $d['jadwal']->cin.' - '.$d['jadwal']->cout }}</em></td>
                  <td class="{{ $d['colorCin'] }}">{{ $d['acin'] }}</td>
                  <td class="{{ $d['colorCout'] }}">{{ $d['acout'] }}</td>
                  <td>{{ $d['alate'] }}</td>
                  <td>{{ $d['aearly'] }}</td>
                  <td>{!! $d['acount'] !!}</td>
                  <td class="text-left desc">{!! nl2br($d['desc']) !!}</td>
                </tr>
              @endforeach
            @endif
          @endforeach
        @endif
      @endforeach
    </tbody>
  </table>
</div>
