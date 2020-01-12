<div class="card">
  <div class="card-header">
    <h3>Data Ibu</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="nama_ibu">Nama</label>
      <input type="text" name="nama_ibu" class="form-control" id="nama_ibu" value="{{ $data->nama_ibu }}" placeholder="Nama">
    </div>
    <div class="form-group">
      <label for="alamat_ibu">Alamat</label>
      <textarea name="alamat_ayah" rows="3" class="form-control" placeholder="Alamat" id="alamat_ibu">{{ $data->alamat_ayah }}</textarea>
    </div>
    <div class="form-group">
      <label for="telp_ibu">Nomor Telepon</label>
      <input type="text" name="telp_ibu" class="form-control" id="telp_ibu" value="{{ $data->telp_ibu }}" placeholder="Nomor Telepon">
    </div>
    <div class="form-group">
      <label for="pekerjaan_ibu">Pekerjaan</label>
      <input type="text" name="pekerjaan_ibu" class="form-control" id="pekerjaan_ibu" value="{{ $data->pekerjaan_ibu }}" placeholder="Pekerjaan">
    </div>
  </div>
</div>
