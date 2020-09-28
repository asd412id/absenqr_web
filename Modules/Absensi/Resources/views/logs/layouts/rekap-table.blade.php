<div class="table-responsive">
  @foreach ($data['rekap'] as $tahun => $dbulan)
    @foreach ($dbulan as $bulan => $duser)
      @php
      $first = array_keys($duser)[0];
      $i = 0;
      @endphp
      <table class="table table-bordered table-absen">
        <caption style="text-align: left;font-style: italic;font-weight: bold;margin-top: 15px;text-transform: uppercase;padding: 3px 7px;border: solid 1px #000" class="bg-primary">{{ strtoupper($bulan).' '.strtoupper($tahun) }}</caption>
        <thead>
          <tr>
            <th rowspan="2">NO.</th>
            <th rowspan="2">NAMA / {{ $data['role']=='pegawai'?'NIP':'NIS' }}</th>
            @if ($data['role']=='pegawai')
              <th rowspan="2">STATUS</th>
              <th rowspan="2">JABATAN</th>
            @else
              <th rowspan="2">KELAS</th>
            @endif
            <th rowspan="2">ABSEN</th>
            <th colspan="{{ count($duser[$first]) }}">TANGGAL</th>
            <th rowspan="2">%</th>
          </tr>
          <tr>
            @foreach ($duser[$first] as $tanggal => $d)
              <th>{{ $tanggal }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($duser as $user => $dd)
            @php
            $u = \App\User::where('uuid',$user)->first();
            $i++;
            @endphp
            <tr>
              <td rowspan="2">{{ $i }}</td>
              <td rowspan="2" style="text-align: left !important;">{!! '<strong>'.$u->name.'</strong><br />'.($u->role=='pegawai'?$u->pegawai->nip:$u->siswa->nis) !!}</td>
              @if ($u->role=='pegawai')
                <td rowspan="2">{{ strtoupper($u->pegawai->status_kepegawaian) }}</td>
                <td rowspan="2">{{ $u->pegawai->jabatan }}</td>
              @else
                <td rowspan="2">{{ $u->siswa->kelas }}</td>
              @endif
              <td class="bg-success">Datang</td>
              @foreach ($dd as $kd => $d)
                <td class="{{ $d['colorCin']??'bg-success' }}">{!! $d['signCin'] !!}</td>
              @endforeach
              <td rowspan="2">{{ $data[$u->uuid]['persentasi'].'%' }}</td>
            </tr>
            <tr>
              <td class="bg-danger">Pulang</td>
              @foreach ($dd as $kd => $d)
                <td class="{{ $d['colorCout']??'bg-danger' }}">{!! $d['signCout'] !!}</td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    @endforeach
  @endforeach

</div>
