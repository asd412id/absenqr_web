<?php

namespace Modules\Absensi\Entities;

use Illuminate\Database\Eloquent\Model;

use Modules\Absensi\Entities\Ruang;
use App\User;

class Jadwal extends Model
{
  protected $table = 'absensi_jadwal';
  protected $fillable = [
    'uuid',
    'nama_jadwal',
    'ruang',
    'hari',
    'cin',
    'cout',
    'start_cin',
    'end_cin',
    'start_cout',
    'end_cout',
    'late',
    'early'
  ];

  public function getCinAttribute($value)
  {
    return $value?date('H:i',strtotime($value)):null;
  }

  public function getStartCinAttribute($value)
  {
    return $value?date('H:i',strtotime($value)):null;
  }

  public function getEndCinAttribute($value)
  {
    return $value?date('H:i',strtotime($value)):null;
  }

  public function getCoutAttribute($value)
  {
    return $value?date('H:i',strtotime($value)):null;
  }

  public function getStartCoutAttribute($value)
  {
    return $value?date('H:i',strtotime($value)):null;
  }

  public function getEndCoutAttribute($value)
  {
    return $value?date('H:i',strtotime($value)):null;
  }

  protected $casts = [
    'hari' => 'array'
  ];

  public function get_ruang()
  {
    return $this->belongsTo(Ruang::class,'ruang');
  }

  public function getNamaHariAttribute()
  {
    $hari = [7=>'Ahad',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jum\'at',6=>'Sabtu'];
    $getHari = [];
    foreach ($this->hari as $key => $value) {
      array_push($getHari,$hari[$value]);
    }
    return $getHari;
  }

  public function user()
  {
    return $this->belongsToMany(User::class,'absensi_jadwal_user','jadwal_id','user_id');
  }
}
