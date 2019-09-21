<div class="card">
  <div class="card-header">
    <h3>Data Kesehatan</h3>
  </div>
  <div class="card-body">
    <table class="table table-striped tbl-info">
      <tr>
        <td width="135">Golongan Darah</td>
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
        <td>Kelainan Jasmani</td>
        <td>:</td>
        <td>{{ $data->kelainan_jasmani??'-' }}</td>
      </tr>
    </table>
  </div>
</div>
