<div class="card">
  <div class="card-header">
    <h3>Data Pribadi</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="nisn">NISN</label>
      <input type="text" name="nisn" class="form-control" id="nisn" value="{{ old('nisn') }}" placeholder="NISN">
    </div>
    <div class="form-group">
      <label for="nis">NIS</label>
      <input type="text" name="nis" class="form-control" id="nis" value="{{ old('nis') }}" placeholder="NIS" required>
    </div>
    <div class="form-group">
      <label for="nama_lengkap">Nama Lengkap</label>
      <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Nama Lengkap" required>
    </div>
    <div class="form-group">
      <label for="nama_panggilan">Nama Panggilan</label>
      <input type="text" name="nama_panggilan" class="form-control" id="nama_panggilan" value="{{ old('nama_panggilan') }}" placeholder="Nama Panggilan">
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="tempat_lahir">Tempat Lahir</label>
          <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Tempat Lahir">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="tanggal_lahir">Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" placeholder="Tanggal Lahir">
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="jenis_kelamin">Jenis Kelamin</label>
      <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
        @php
        $data = [
          1 => 'Laki - Laki',
          2 => 'Perempuan'
        ];
        @endphp
        @foreach ($data as $key => $v)
          <option {{ old('jenis_kelamin')==$key?'selected':'' }} value="{{ $key }}">{{ $v }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="agama">Agama</label>
      <select class="form-control" name="agama" id="agama">
        @php
        $data = [
          'islam',
          'kristen',
          'hindu',
          'budha',
          'konghucu',
        ];
        @endphp
        @foreach ($data as $key => $v)
          <option {{ old('agama')==$v?'selected':'' }} value="{{ $v }}">{{ ucwords($v) }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="alamat">Alamat</label>
      <textarea class="form-control" name="alamat" placeholder="Alamat" id="alamat" rows="3">{{ old('alamat') }}</textarea>
    </div>
    <div class="form-group">
      <label for="kewarganegaraan">Kewarganegaraan</label>
      <select class="form-control" name="kewarganegaraan" id="kewarganegaraan">
        @php
        $data = [
          'WNI',
          'WNA',
        ];
        @endphp
        @foreach ($data as $key => $v)
          <option {{ old('kewarganegaraan')==$v?'selected':'' }} value="{{ $v }}">{{ ucwords($v) }}</option>
        @endforeach
      </select>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="jumlah_saudara">Jumlah Saudara Kandung</label>
          <input type="number" name="jumlah_saudara" class="form-control" id="jumlah_saudara" value="{{ old('jumlah_saudara') }}" placeholder="Jumlah Saudara">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="anak_ke">Anak Ke</label>
          <input type="number" name="anak_ke" class="form-control" id="anak_ke" value="{{ old('anak_ke') }}" placeholder="Anak Ke">
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="bahasa_hari">Bahasa Sehari-hari</label>
      <input type="text" name="bahasa_hari" class="form-control" id="bahasa_hari" value="{{ old('bahasa_hari') }}" placeholder="Indonesia, Bugis, ...">
    </div>
    <div class="form-group">
      <label for="telp">Nomor Telepon</label>
      <input type="text" name="telp" class="form-control" id="telp" value="{{ old('telp') }}" placeholder="Nomor Telepon">
    </div>
    <div class="form-group">
      <label>Upload Foto</label>
      <input type="file" name="foto" class="file-upload-default" accept=".jpeg,.jpg,.png">
      <div class="input-group col-xs-12">
        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Foto">
        <span class="input-group-append">
          <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
        </span>
      </div>
    </div>
  </div>
</div>
