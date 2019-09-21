<div class="card">
  <div class="card-header">
    <h3>Data Kesehatan</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="golda">Golongan Darah</label>
      <select class="form-control" name="golda" id="golda">
        @php
        $data = [
          'A',
          'B',
          'AB',
          'O',
        ];
        @endphp
        @foreach ($data as $key => $v)
          <option {{ old('golda')==$v?'selected':'' }} value="{{ $v }}">{{ ucwords($v) }}</option>
        @endforeach
      </select>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="tinggi">Tinggi Badan</label>
          <div class="input-group">
            <input type="number" name="tinggi" class="form-control" id="tinggi" value="{{ old('tinggi') }}" placeholder="Contoh: 150">
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
            <input type="number" name="berat" class="form-control" id="berat" value="{{ old('berat') }}" placeholder="Contoh: 45">
            <span class="input-group-append">
              <label class="input-group-text">Kg</label>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="kelainan_jasmani">Kelainan Jasmani</label>
      <input type="text" name="kelainan_jasmani" class="form-control" id="kelainan_jasmani" value="{{ old('kelainan_jasmani') }}" placeholder="Sumbing, Patah tulang, ...">
    </div>
  </div>
</div>
