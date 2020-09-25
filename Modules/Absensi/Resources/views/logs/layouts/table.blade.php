@foreach ($data as $key => $days)
  @php
  $dy = \Carbon\Carbon::createFromFormat('d/m/Y',$key);
  @endphp
  <table class="table table-bordered table-absen">
    <caption style="text-align: left;font-style: italic;font-weight: bold;margin-top: 15px;text-transform: uppercase;padding: 3px 7px;border: solid 1px #000" class="bg-primary">{{ $dy->locale('id')->translatedFormat('l, d F Y') }}</caption>
    <thead>
      <tr>
        <th width="300">Nama</th>
        <th>Jadwal</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Terlambat</th>
        <th>Pulang Cepat</th>
        <th style="white-space: nowrap">Jumlah Jam</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      @php
      $ddone = [];
      @endphp
      <tr>
        @foreach ($days as $key1 => $users)
          @php
          array_push($ddone,$key1);
          $udone = [];
          @endphp
          <td class="nowrap" style="vertical-align: top;text-align: left;font-weight: bold" rowspan="{{ count($users) }}">{{ explode('_',$key1)[0] }}</td>
          @foreach ($users as $key2 => $user)
            @php
            array_push($udone,$key2)
            @endphp
            <td class="text-left" width="100" style="text-align: left !important"><em><span class="font-weight-bold badge badge-dark p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->nama_jadwal }}</span></em><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->cin.' - '.$user['jadwal']->cout }}</em></td>
            <td width="100" class="{{ $user['colorCin'] }}">{{ $user['acin'] }}</td>
            <td width="100" class="{{ $user['colorCout'] }}">{{ $user['acout'] }}</td>
            <td width="100">{{ $user['alate'] }}</td>
            <td width="100">{{ $user['aearly'] }}</td>
            <td width="100">{!! $user['acount'] !!}</td>
            <td class="text-left desc">{!! nl2br($user['desc']) !!}</td>
            @break(count($users)>1)
          @endforeach
          @if (count($users)>1)
            @foreach ($users as $key2 => $user)
              @continue(in_array($key2,$udone))
              <tr>
                <td width="100" class="text-left" style="text-align: left !important"><em><span class="font-weight-bold badge badge-dark p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->nama_jadwal }}</span></em><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->cin.' - '.$user['jadwal']->cout }}</em></td>
                <td width="100" class="{{ $user['colorCin'] }}">{{ $user['acin'] }}</td>
                <td width="100" class="{{ $user['colorCout'] }}">{{ $user['acout'] }}</td>
                <td width="100">{{ $user['alate'] }}</td>
                <td width="100">{{ $user['aearly'] }}</td>
                <td width="100">{!! $user['acount'] !!}</td>
                <td class="text-left desc">{!! nl2br($user['desc']) !!}</td>
              </tr>
            @endforeach
          @endif
          @break(count($days)>1)
        @endforeach
      </tr>
      @if (count($days)>1)
        @foreach ($days as $key1 => $users)
          @continue(in_array($key1,$ddone))
          <tr>
            <td class="nowrap" style="vertical-align: top;text-align: left;font-weight: bold" rowspan="{{ count($users) }}">{{ explode('_',$key1)[0] }}</td>
            @foreach ($users as $key2 => $user)
              @php
                array_push($udone,$key2)
              @endphp
              <td class="text-left" style="text-align: left !important"><em><span class="font-weight-bold badge badge-dark p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->nama_jadwal }}</span></em><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->cin.' - '.$user['jadwal']->cout }}</em></td>
              <td class="{{ $user['colorCin'] }}">{{ $user['acin'] }}</td>
              <td class="{{ $user['colorCout'] }}">{{ $user['acout'] }}</td>
              <td>{{ $user['alate'] }}</td>
              <td>{{ $user['aearly'] }}</td>
              <td>{!! $user['acount'] !!}</td>
              <td class="text-left desc">{!! nl2br($user['desc']) !!}</td>
              @break(count($users)>1)
            @endforeach
          </tr>
          @if (count($users)>1)
            @foreach ($users as $key2 => $user)
              @continue(in_array($key2,$udone))
              <tr>
                <td class="text-left" style="text-align: left !important"><em><span class="font-weight-bold badge badge-dark p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->nama_jadwal }}</span></em><br><em class="font-weight-bold badge badge-primary p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->get_ruang->nama_ruang }}</em><br><em class="font-weight-bold badge badge-success p0" style="font-size: 0.8em;padding: 3px 7px !important">{{ $user['jadwal']->cin.' - '.$user['jadwal']->cout }}</em></td>
                <td class="{{ $user['colorCin'] }}">{{ $user['acin'] }}</td>
                <td class="{{ $user['colorCout'] }}">{{ $user['acout'] }}</td>
                <td>{{ $user['alate'] }}</td>
                <td>{{ $user['aearly'] }}</td>
                <td>{!! $user['acount'] !!}</td>
                <td class="text-left desc">{!! nl2br($user['desc']) !!}</td>
              </tr>
            @endforeach
          @endif
        @endforeach
      @endif
    </tbody>
  </table>
@endforeach
