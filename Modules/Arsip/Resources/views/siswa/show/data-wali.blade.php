<div class="card">
  <div class="card-header">
    <h3>Data Wali</h3>
  </div>
  <div class="card-body">
    <table class="table table-striped tbl-info">
      <tr>
        <td width="95">Nama</td>
        <td width="1">:</td>
        <td>{{ $data->nama_wali??'-' }}</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data->alamat_wali??'-' }}</td>
      </tr>
      <tr>
        <td>No. Telepon</td>
        <td>:</td>
        <td>{{ $data->telp_wali??'-' }}</td>
      </tr>
      <tr>
        <td>Pekerjaan</td>
        <td>:</td>
        <td>{{ $data->pekerjaan_wali??'-' }}</td>
      </tr>
    </table>
  </div>
</div>
