<div class="card">
  <div class="card-header">
    <h3>Data Riwayat Pendidikan</h3>
  </div>
  <div class="card-body">
    <table class="table table-striped tbl-info">
      <thead>
        <th>No</th>
        <th>Nama Lembaga</th>
        <th>Status Lembaga</th>
        <th>Tahun Ijazah</th>
      </thead>
      <tbody>
        @php
        $riwayat_pendidikan = json_decode($data->riwayat_pendidikan);
        @endphp
        @if (!count($riwayat_pendidikan))
          <tr>
            <td colspan="4" class="text-center">Riwayat pendidikan tidak tersedia</td>
          </tr>
        @else
          @foreach ($riwayat_pendidikan as $key => $v)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>{{ $v->nama_pendidikan }}</td>
              <td>{{ ucwords($v->status_pendidikan) }}</td>
              <td>{{ $v->tahun_ijazah }}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </div>
</div>
