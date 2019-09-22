<div class="card">
  <div class="card-header">
    <h3>Data Pribadi</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-2">
        <img src="{{ $data->foto?asset('uploaded/'.$data->foto):url('assets/img/avatar.png') }}" alt="" class="img-fluid rounded foto">
        <div class="clearfix"></div>
        <div class="mt-2 text-center">
          <a href="{{ route('pegawai.export.single.pdf',['uuid'=>$data->uuid]) }}" class="btn btn-success" target="_blank"><i class="fas fa-file-pdf"></i> Ekspor PDF</a>
          <a href="{{ route('pegawai.edit',['uuid'=>$data->uuid]) }}" class="btn mt-1 btn-primary"><i class="ik ik-edit"></i> Ubah Data</a>
          <a href="{{ route('pegawai.destroy',['uuid'=>$data->uuid]) }}" class="btn mt-1 btn-danger hapus"><i class="ik ik-trash-2"></i> Hapus Data</a>
        </div>
      </div>
      <div class="col-md-10">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-striped tbl-info">
              <tr>
                <td width="155">Nama Lengkap</td>
                <td width="1">:</td>
                <td>{{ $data->nama??'-' }}</td>
              </tr>
              <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $data->tempat_lahir??'-' }}, {{ $data->tanggal_lahir?date('d-m-Y',strtotime($data->tanggal_lahir)):'-' }}</td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $data->jenis_kelamin===1?'Laki - Laki':'Perempuan' }}</td>
              </tr>
              <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ ucwords($data->agama)??'-' }}</td>
              </tr>
              <tr>
                <td>Status Kawin</td>
                <td>:</td>
                <td>{{ ucwords($data->status_kawin)??'-' }}</td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $data->alamat??'-' }}</td>
              </tr>
              <tr>
                <td>Nomor Telepon</td>
                <td>:</td>
                <td>{{ $data->telp??'-' }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-striped tbl-info">
              <tr>
                <td width="145">Pendidikan Terakhir</td>
                <td width="1">:</td>
                <td>{{ $data->pendidikan_akhir??'-' }}</td>
              </tr>
              <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $data->jabatan??'-' }}</td>
              </tr>
              <tr>
                <td>Status Kepegawaian</td>
                <td>:</td>
                <td>{{ strtoupper($data->status_kepegawaian)??'-' }}</td>
              </tr>
              <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $data->nip??'-' }}</td>
              </tr>
              <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td>{{ $data->unit_kerja??'-' }}</td>
              </tr>
              <tr>
                <td>Mulai Masuk</td>
                <td>:</td>
                <td>{{ $data->mulai_masuk?date('d-m-Y',strtotime($data->mulai_masuk)):'-' }}</td>
              </tr>
              <tr>
                <td>Kegemaran/Hobi</td>
                <td>:</td>
                <td>{{ $data->kegemaran??'-' }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
