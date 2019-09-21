<?php

namespace Modules\Arsip\Entities;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
  protected $table = 'pegawai';
  protected $fillable = [
    'uuid',
    'nama',
    'nip',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'agama',
    'status_kawin',
    'alamat',
    'jabatan',
    'status_kepegawaian',
    'pangkat_golongan',
    'unit_kerja',
    'mulai_masuk',
    'golda',
    'tinggi',
    'berat',
    'rambut',
    'bentuk_muka',
    'warna_muka',
    'ciri_ciri',
    'kegemaran',
    'pendidikan_akhir',
    'riwayat_pendidikan',
    'foto',
  ];

  public function user()
  {
    return $this->hasOne(User::class,'id_user');
  }
}
