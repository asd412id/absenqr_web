<div class="card">
  <div class="card-header">
    <h3>Data Ayah</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="nama_ayah">Nama</label>
      <input type="text" name="nama_ayah" class="form-control" id="nama_ayah" value="{{ $data->nama_ayah }}" placeholder="Nama">
    </div>
    <div class="form-group">
      <label for="alamat_ayah">Alamat</label>
      <textarea name="alamat_ayah" rows="3" class="form-control" placeholder="Alamat" id="alamat_ayah">{{ $data->alamat_ayah }}</textarea>
    </div>
    <div class="form-group">
      <label for="telp_ayah">Nomor Telepon</label>
      <input type="text" name="telp_ayah" class="form-control" id="telp_ayah" value="{{ $data->telp_ayah }}" placeholder="Nomor Telepon">
    </div>
    <div class="form-group">
      <label for="pekerjaan_ayah">Pekerjaan</label>
      <input type="text" name="pekerjaan_ayah" class="form-control" id="pekerjaan_ayah" value="{{ $data->pekerjaan_ayah }}" placeholder="Pekerjaan">
    </div>
  </div>
</div>
