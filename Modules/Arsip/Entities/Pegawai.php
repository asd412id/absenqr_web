<?php

namespace Modules\Arsip\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

use Carbon\Carbon;

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

  public function getTanggalLahirAttribute($value)
  {
    return $value?date('d-m-Y',strtotime($value)):null;
  }

  public function getMulaiMasukAttribute($value)
  {
    return $value?date('d-m-Y',strtotime($value)):null;
  }

  public function setTanggalLahirAttribute($value)
  {
    $this->attributes['tanggal_lahir'] = $value?Carbon::createFromFormat('d-m-Y',$value)->format('Y-m-d'):null;
  }
  public function setMulaiMasukAttribute($value)
  {
    $this->attributes['mulai_masuk'] = $value?Carbon::createFromFormat('d-m-Y',$value)->format('Y-m-d'):null;
  }

  public function user()
  {
    return $this->hasOne(User::class,'id_user')->where('role','pegawai');
  }
}
