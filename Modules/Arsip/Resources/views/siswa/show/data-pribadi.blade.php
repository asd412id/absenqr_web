<div class="card">
  <div class="card-header">
    <h3>Data Pribadi</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-2 text-center">
        <img src="{{ $data->foto?asset('uploaded/'.$data->foto):url('assets/img/avatar.png') }}" alt="" class="img-fluid rounded foto">
        <div class="clearfix"></div>
        <div class="mt-2 text-center">
          <a href="{{ route('siswa.export.single.pdf',['uuid'=>$data->uuid]) }}" class="btn btn-success" target="_blank"><i class="fas fa-file-pdf"></i> Ekspor PDF</a>
          <a href="{{ route('siswa.edit',['uuid'=>$data->uuid]) }}" class="btn mt-1 btn-primary"><i class="ik ik-edit"></i> Ubah Data</a>
          <a href="{{ route('siswa.destroy',['uuid'=>$data->uuid]) }}" class="btn mt-1 mb-2 btn-danger hapus"><i class="ik ik-trash-2"></i> Hapus Data</a>
        </div>
      </div>
      <div class="col-md-10">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-striped tbl-info">
              <tr>
                <td width="175">NISN</td>
                <td width="1">:</td>
                <td>{{ $data->nisn??'-' }}</td>
              </tr>
              <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{ $data->nis??'-' }}</td>
              </tr>
              <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $data->nama_lengkap??'-' }}</td>
              </tr>
              <tr>
                <td>Nama Panggilan</td>
                <td>:</td>
                <td>{{ $data->nama_panggilan??'-' }}</td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $data->jenis_kelamin===1?'Laki - Laki':'Perempuan' }}</td>
              </tr>
              <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $data->tempat_lahir??'-' }}, {{ $data->tanggal_lahir?date('d-m-Y',strtotime($data->tanggal_lahir)):'-' }}</td>
              </tr>
              <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ ucwords($data->agama)??'-' }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-striped tbl-info">
              <tr>
                <td width="175">Kewarganegaraan</td>
                <td width="1">:</td>
                <td>{{ $data->kewarganegaraan??'-' }}</td>
              </tr>
              <tr>
                <td>Jumlah Saudara Kandung</td>
                <td>:</td>
                <td>{{ $data->jumlah_saudara??'-' }} Orang</td>
              </tr>
              <tr>
                <td>Anak Ke</td>
                <td>:</td>
                <td>{{ $data->anak_ke??'-' }}</td>
              </tr>
              <tr>
                <td>Bahasa Sehari-hari</td>
                <td>:</td>
                <td>{{ $data->bahasa_hari??'-' }}</td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $data->alamat??'-' }}</td>
              </tr>
              <tr>
                <td>No. Telepon</td>
                <td>:</td>
                <td>{{ $data->telp??'-' }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
