<div class="card">
  <div class="card-header">
    <h3>Data Sekolah</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="asal_sekolah">Asal Sekolah</label>
      <input type="text" name="asal_sekolah" class="form-control" id="asal_sekolah" value="{{ $data->asal_sekolah }}" placeholder="Asal Sekolah">
    </div>
    <div class="form-group">
      <label for="tanggal_diterima">Tanggal Diterima di Sekolah Ini</label>
      <input type="date" name="tanggal_diterima" class="form-control" id="tanggal_diterima" value="{{ $data->tanggal_diterima }}" placeholder="Tanggal Diterima">
    </div>
    <div class="form-group">
      <label for="kelas">Kelas</label>
      <input type="text" name="kelas" class="form-control" id="kelas" value="{{ $data->kelas }}" placeholder="Kelas">
    </div>
    <div class="form-group">
      <label for="tanggal_tamat">Tanggal Tamat dari Sekolah Ini</label>
      <input type="date" name="tanggal_tamat" class="form-control" id="tanggal_tamat" value="{{ $data->tanggal_tamat }}" placeholder="Tanggal Tamat">
    </div>
    <div class="form-group">
      <label for="nomor_ijazah">Nomor Ijazah</label>
      <input type="text" name="nomor_ijazah" class="form-control" id="nomor_ijazah" value="{{ $data->nomor_ijazah }}" placeholder="Nomor Ijazah">
    </div>
    <div class="form-group">
      <label for="tanggal_ijazah">Tanggal Ijazah</label>
      <input type="date" name="tanggal_ijazah" class="form-control" id="tanggal_ijazah" value="{{ $data->tanggal_ijazah }}" placeholder="Tanggal Ijazah">
    </div>
    <div class="form-group">
      <label for="pendidikan_lanjut">Melanjutkan Pendidikan di</label>
      <input type="text" name="pendidikan_lanjut" class="form-control" id="pendidikan_lanjut" value="{{ $data->asal_sekolah }}" placeholder="Melanjutkan Pendidikan di">
    </div>
  </div>
</div>
