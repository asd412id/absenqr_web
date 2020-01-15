<div class="card">
  <div class="card-header">
    <h3>Data Wali</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="nama_wali">Nama</label>
      <input type="text" name="nama_wali" class="form-control" id="nama_wali" value="{{ old('nama_wali') }}" placeholder="Nama">
    </div>
    <div class="form-group">
      <label for="alamat_wali">Alamat</label>
      <textarea name="alamat_ayah" rows="3" class="form-control" placeholder="Alamat" id="alamat_wali">{{ old('alamat_wali') }}</textarea>
    </div>
    <div class="form-group">
      <label for="telp_wali">Nomor Telepon</label>
      <input type="text" name="telp_wali" class="form-control" id="telp_wali" value="{{ old('telp_wali') }}" placeholder="Nomor Telepon">
    </div>
    <div class="form-group">
      <label for="pekerjaan_wali">Pekerjaan</label>
      <input type="text" name="pekerjaan_wali" class="form-control" id="pekerjaan_wali" value="{{ old('pekerjaan_wali') }}" placeholder="Pekerjaan">
    </div>
  </div>
</div>
