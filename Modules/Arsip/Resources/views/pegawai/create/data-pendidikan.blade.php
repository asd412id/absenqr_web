<div class="card">
  <div class="card-header">
    <h3>Data Pendidikan</h3>
  </div>
  <div class="card-body">
    <div class="form-inline pendidikan">
      <div data-repeater-list="riwayat_pendidikan">
        <div data-repeater-item class="d-flex mb-2">
          <label class="sr-only">Data Pendidikan</label>
          @if (old('riwayat_pendidikan'))
            @foreach (old('riwayat_pendidikan') as $key => $v)
              <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                <input type="text" class="form-control" name="riwayat_pendidikan[{{ $key }}][nama_pendidikan]" placeholder="Nama Pendidikan" value="{{ $v['nama_pendidikan'] }}">
              </div>
              <div class="form-group mb-2 mr-sm-2 mb-sm-0">
                <select class="form-control" name="riwayat_pendidikan[{{ $key }}][status_pendidikan]">
                  <option {{ $v['status_pendidikan']=='negeri'?'selected':'' }} value="negeri">Negeri</option>
                  <option {{ $v['status_pendidikan']=='swasta'?'selected':'' }} value="swasta">Swasta</option>
                </select>
              </div>
              <div class="form-group mb-2 mb-sm-0">
                <input type="year" class="form-control" name="riwayat_pendidikan[{{ $key }}][tahun_ijazah]" placeholder="Tahun Ijazah" value="{{ $v['tahun_ijazah'] }}">
              </div>
            @endforeach
          @endif
          <div class="form-group mb-2 mr-sm-2 mb-sm-0">
            <input type="text" class="form-control" name="nama_pendidikan" placeholder="Nama Pendidikan">
          </div>
          <div class="form-group mb-2 mr-sm-2 mb-sm-0">
            <select class="form-control" name="status_pendidikan">
              <option value="negeri">Negeri</option>
              <option value="swasta">Swasta</option>
            </select>
          </div>
          <div class="form-group mb-2 mb-sm-0">
            <input type="year" class="form-control" name="tahun_ijazah" placeholder="Tahun Ijazah">
          </div>
          <button data-repeater-delete type="button" class="btn btn-danger btn-icon ml-1" ><i class="ik ik-trash-2"></i></button>
        </div>
      </div>
      <button data-repeater-create type="button" class="btn btn-success btn-icon ml-2 mb-2"><i class="ik ik-plus"></i></button>
    </div>
  </div>
</div>
