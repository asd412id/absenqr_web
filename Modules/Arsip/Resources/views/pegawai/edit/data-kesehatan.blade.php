<div class="card">
  <div class="card-header">
    <h3>Keterangan Diri</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="golda">Golongan Darah</label>
      <select class="form-control" name="golda" id="golda">
        @php
        $datas = [
          'A',
          'B',
          'AB',
          'O',
        ];
        @endphp
        @foreach ($datas as $key => $v)
          <option {{ $data->golda==$v?'selected':'' }} value="{{ $v }}">{{ ucwords($v) }}</option>
        @endforeach
      </select>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="tinggi">Tinggi Badan</label>
          <div class="input-group">
            <input type="number" name="tinggi" class="form-control" id="tinggi" value="{{ $data->tinggi }}" placeholder="Contoh: 150">
            <span class="input-group-append">
              <label class="input-group-text">cm</label>
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="berat">Berat Badan</label>
          <div class="input-group">
            <input type="number" name="berat" class="form-control" id="berat" value="{{ $data->berat }}" placeholder="Contoh: 45">
            <span class="input-group-append">
              <label class="input-group-text">Kg</label>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="rambut">Rambut</label>
      <input type="text" name="rambut" class="form-control" id="rambut" value="{{ $data->rambut }}" placeholder="lurus hitam, berombak hitam, ...">
    </div>
    <div class="form-group">
      <label for="bentuk_muka">Bentuk Muka</label>
      <input type="text" name="bentuk_muka" class="form-control" id="bentuk_muka" value="{{ $data->bentuk_muka }}" placeholder="bulat, lonjong, ...">
    </div>
    <div class="form-group">
      <label for="warna_muka">Warna Muka</label>
      <input type="text" name="warna_muka" class="form-control" id="warna_muka" value="{{ $data->warna_muka }}" placeholder="hitam, sawo matang, kuning, ...">
    </div>
    <div class="form-group">
      <label for="ciri_ciri">Ciri-Ciri Khas</label>
      <input type="text" name="ciri_ciri" class="form-control" id="ciri_ciri" value="{{ $data->ciri_ciri }}" placeholder="Ciri-ciri khas">
    </div>
    <div class="form-group">
      <label for="kegemaran">Kegemaran/Hobi</label>
      <input type="text" name="kegemaran" class="form-control" id="kegemaran" value="{{ $data->kegemaran }}" placeholder="Kegemaran/hobi">
    </div>
  </div>
</div>
