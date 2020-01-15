<?php

namespace Modules\Absensi\Entities;

use Modules\Absensi\Entities\Jadwal;
use Modules\Absensi\Entities\AbsensiLogs;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Ruang extends Model
{
  protected $table = 'absensi_ruang';
  protected $fillable = ['uuid','nama_ruang','desc'];
  protected $hidden = ['_token'];

  public function jadwal()
  {
    return $this->hasMany(Jadwal::class,'ruang');
  }

  public function absen()
  {
    return  $this->hasMany(AbsensiLogs::class,'ruang_id');
  }

  public function absenUser()
  {
    return $this->belongsToMany(User::class,'absensi_log','ruang_id','user_id')->withTimestamps();
  }
}
