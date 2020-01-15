<div class="card">
  <div class="card-header">
    <h3>Keterangan Diri</h3>
  </div>
  <div class="card-body">
    <table class="table table-striped tbl-info">
      <tr>
        <td width="125">Golongan Darah</td>
        <td width="1">:</td>
        <td>{{ $data->golda??'-' }}</td>
      </tr>
      <tr>
        <td>Tinggi Badan</td>
        <td>:</td>
        <td>{{ $data->tinggi??'-' }} cm</td>
      </tr>
      <tr>
        <td>Berat Badan</td>
        <td>:</td>
        <td>{{ $data->berat??'-' }} Kg</td>
      </tr>
      <tr>
        <td>Rambut</td>
        <td>:</td>
        <td>{{ $data->rambut??'-' }}</td>
      </tr>
      <tr>
        <td>Bentuk Muka</td>
        <td>:</td>
        <td>{{ $data->bentuk_muka??'-' }}</td>
      </tr>
      <tr>
        <td>Warna Muka</td>
        <td>:</td>
        <td>{{ $data->warna_muka??'-' }}</td>
      </tr>
      <tr>
        <td>Ciri - Ciri Khas</td>
        <td>:</td>
        <td>{{ $data->ciri_ciri??'-' }}</td>
      </tr>
    </table>
  </div>
</div>
