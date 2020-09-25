<div class="card">
  <div class="card-header">
    <h3>Data Pribadi</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="nama">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control" id="nama" value="{{ $data->nama }}" placeholder="Nama Lengkap" required>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="tempat_lahir">Tempat Lahir</label>
          <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir" value="{{ $data->tempat_lahir }}" placeholder="Tempat Lahir">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="tanggal_lahir">Tanggal Lahir</label>
          <input type="text" name="tanggal_lahir" class="form-control datepicker" id="tanggal_lahir" value="{{ $data->tanggal_lahir }}" placeholder="Tanggal Lahir">
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="jenis_kelamin">Jenis Kelamin</label>
      <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
        @php
        $datas = [
          1 => 'Laki - Laki',
          2 => 'Perempuan'
        ];
        @endphp
        @foreach ($datas as $key => $v)
          <option {{ $data->jenis_kelamin==$key?'selected':'' }} value="{{ $key }}">{{ $v }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="agama">Agama</label>
      <select class="form-control" name="agama" id="agama">
        @php
        $datas = [
          'islam',
          'kristen',
          'hindu',
          'budha',
          'konghucu',
        ];
        @endphp
        @foreach ($datas as $key => $v)
          <option {{ $data->agama==$v?'selected':'' }} value="{{ $v }}">{{ ucwords($v) }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>Status Kawin</label>
      <div class="form-radio">
        <div class="radio radio-default radio-inline">
          <label>
            <input type="radio" name="status_kawin" value="belum" {{ $data->status_kawin=='belum'?'checked':'checked' }}>
            <i class="helper"></i>Belum
          </label>
        </div>
        <div class="radio radio-default radio-inline">
          <label>
            <input type="radio" name="status_kawin" value="kawin" {{ $data->status_kawin=='kawin'?'checked':'' }}>
            <i class="helper"></i>Kawin
          </label>
        </div>
        <div class="radio radio-default radio-inline">
          <label>
            <input type="radio" name="status_kawin" value="janda" {{ $data->status_kawin=='janda'?'checked':'' }}>
            <i class="helper"></i>Janda
          </label>
        </div>
        <div class="radio radio-default radio-inline">
          <label>
            <input type="radio" name="status_kawin" value="duda" {{ $data->status_kawin=='duda'?'checked':'' }}>
            <i class="helper"></i>Duda
          </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="alamat">Alamat</label>
      <textarea class="form-control" name="alamat" placeholder="Alamat" id="alamat" rows="3">{{ $data->alamat }}</textarea>
    </div>
    <div class="form-group">
      <label for="telp">Nomor Telepon</label>
      <input type="text" name="telp" class="form-control" id="telp" value="{{ $data->telp }}" placeholder="Nomor Telepon">
    </div>
    <div class="form-group">
      <label for="pendidikan_akhir">Pendidikan Terakhir</label>
      <input type="text" name="pendidikan_akhir" class="form-control" id="pendidikan_akhir" value="{{ $data->pendidikan_akhir }}" placeholder="SMA, S-1 Pendidikan, ...">
    </div>
    <div class="form-group">
      <label for="jabatan">Jabatan</label>
      <input type="text" name="jabatan" class="form-control" id="jabatan" value="{{ $data->jabatan }}" placeholder="Jabatan">
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Status Kepegawaian</label>
          <div class="form-radio">
            <div class="radio radio-default radio-inline">
              <label>
                <input type="radio" name="status_kepegawaian" value="pns" {{ $data->status_kepegawaian=='pns'?'checked':'checked' }}>
                <i class="helper"></i>PNS
              </label>
            </div>
            <div class="radio radio-default radio-inline">
              <label>
                <input type="radio" name="status_kepegawaian" value="gtt" {{ $data->status_kepegawaian=='gtt'?'checked':'' }}>
                <i class="helper"></i>GTT
              </label>
            </div>
            <div class="radio radio-default radio-inline">
              <label>
                <input type="radio" name="status_kepegawaian" value="ptt" {{ $data->status_kepegawaian=='ptt'?'checked':'' }}>
                <i class="helper"></i>PTT
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="pangkat_golongan">Pangkat/Golongan</label>
          @php
          $pg = [
            'I/a',
            'I/b',
            'I/c',
            'I/d',
            'II/a',
            'II/b',
            'II/c',
            'II/d',
            'III/a',
            'III/b',
            'III/c',
            'III/d',
            'IV/a',
            'IV/b',
            'IV/c',
            'IV/d',
            'IV/e',
          ];
          @endphp
          <select class="form-control" id="pangkat_golongan" name="pangkat_golongan">
            <option value="">Pangkat/Golongan</option>
            @foreach ($pg as $key => $p)
              <option {{ $data->pangkat_golongan==$p?'selected':'' }} value="{{ $p }}">{{ $p }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="nuptk">NUPTK</label>
      <input type="text" name="nuptk" class="form-control" id="nuptk" value="{{ $data->nuptk }}" placeholder="NUPTK">
    </div>
    <div class="form-group">
      <label for="nip">NIP</label>
      <input type="text" name="nip" class="form-control" id="nip" value="{{ $data->nip }}" placeholder="NIP">
    </div>
    <div class="form-group">
      <label for="unit_kerja">Unit Kerja</label>
      <input type="text" name="unit_kerja" class="form-control" id="unit_kerja" value="{{ $data->unit_kerja }}" placeholder="Unit Kerja">
    </div>
    <div class="form-group">
      <label for="mulai_masuk">Mulai Masuk</label>
      <input type="text" name="mulai_masuk" class="form-control datepicker" id="mulai_masuk" value="{{ $data->mulai_masuk }}" placeholder="Mulai Masuk">
    </div>
    <div class="form-group">
      <label>Ubah Foto</label>
      @if ($data->foto)
        <div class="clearfix"></div>
        <img src="{{ asset('uploaded/'.$data->foto) }}" alt="" class="img-fluid rounded" width="150" height="225">
        <div class="clearfix mb-2"></div>
      @endif
      <input type="file" name="foto" class="file-upload-default" accept=".jpeg,.jpg,.png">
      <div class="input-group col-xs-12">
        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Foto untuk mengganti foto lama">
        <span class="input-group-append">
          <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
        </span>
      </div>
    </div>
  </div>
</div>
