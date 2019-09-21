<div class="card">
  <div class="card-header">
    <h3>Data Sekolah</h3>
  </div>
  <div class="card-body">
    <table class="table table-striped tbl-info">
      <tr>
        <td width="165">Asal Sekolah</td>
        <td width="1">:</td>
        <td>{{ $data->asal_sekolah??'-' }}</td>
      </tr>
      <tr>
        <td>Tanggal diterima</td>
        <td>:</td>
        <td>{{ $data->tanggal_diterima?date('d-m-Y',strtotime($data->tanggal_diterima)):'-' }}</td>
      </tr>
      <tr>
        <td>Kelas</td>
        <td>:</td>
        <td>{{ $data->kelas??'-' }}</td>
      </tr>
      <tr>
        <td>Tanggal Tamat</td>
        <td>:</td>
        <td>{{ $data->tanggal_tamat?date('d-m-Y',strtotime($data->tanggal_tamat)):'-' }}</td>
      </tr>
      <tr>
        <td>Nomor Ijazah</td>
        <td>:</td>
        <td>{{ $data->nomor_ijazah??'-' }}</td>
      </tr>
      <tr>
        <td>Tanggal Ijazah</td>
        <td>:</td>
        <td>{{ $data->tanggal_ijazah?date('d-m-Y',strtotime($data->tanggal_ijazah)):'-' }}</td>
      </tr>
      <tr>
        <td>Pendidikan Selanjutnya</td>
        <td>:</td>
        <td>{{ $data->pendidikan_lanjut??'-' }}</td>
      </tr>
    </table>
  </div>
</div>
