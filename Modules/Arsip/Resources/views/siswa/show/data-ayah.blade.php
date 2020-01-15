<div class="card">
  <div class="card-header">
    <h3>Data Ayah</h3>
  </div>
  <div class="card-body">
    <table class="table table-striped tbl-info">
      <tr>
        <td width="95">Nama</td>
        <td width="1">:</td>
        <td>{{ $data->nama_ayah??'-' }}</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>{{ $data->alamat_ayah??'-' }}</td>
      </tr>
      <tr>
        <td>No. Telepon</td>
        <td>:</td>
        <td>{{ $data->telp_ayah??'-' }}</td>
      </tr>
      <tr>
        <td>Pekerjaan</td>
        <td>:</td>
        <td>{{ $data->pekerjaan_ayah??'-' }}</td>
      </tr>
    </table>
  </div>
</div>
