<?php

namespace Modules\Arsip\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Siswa extends Model
{
  protected $table = 'siswa';
  protected $fillable = [
    'uuid',
    'nisn',
    'nis',
    'kelas',
    'nama_lengkap',
    'nama_panggilan',
    'jenis_kelamin',
    'tempat_lahir',
    'tanggal_lahir',
    'agama',
    'kewarganegaraan',
    'anak_ke',
    'jumlah_saudara',
    'bahasa_hari',
    'alamat',
    'telp',
    'golda',
    'tinggi',
    'berat',
    'kelainan_jasmani',
    'asal_sekolah',
    'tanggal_diterima',
    'nama_ayah',
    'alamat_ayah',
    'telp_ayah',
    'pekerjaan_ayah',
    'nama_ibu',
    'alamat_ibu',
    'telp_ibu',
    'pekerjaan_ibu',
    'nama_wali',
    'alamat_wali',
    'telp_wali',
    'pekerjaan_wali',
    'tanggal_tamat',
    'tanggal_ijazah',
    'nomor_ijazah',
    'pendidikan_lanjut',
    'foto',
  ];

  public function user()
  {
    return $this->hasOne(User::class,'id_user');
  }
}
