<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Modules\Absensi\Entities\Jadwal;
use Modules\Absensi\Entities\Ruang;
use Modules\Absensi\Entities\AbsensiLogs;
use Modules\Absensi\Entities\AbsensiDesc;

use Modules\Arsip\Entities\Pegawai;
use Modules\Arsip\Entities\Siswa;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password', 'role', 'id_user','api_token','activate_key','active','changed_password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token','activate_key',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pegawai()
    {
      return $this->belongsTo(Pegawai::class,'id_user');
    }
    public function siswa()
    {
      return $this->belongsTo(Siswa::class,'id_user');
    }

    public function jadwal()
    {
      return $this->belongsToMany(Jadwal::class,'absensi_jadwal_user','user_id','jadwal_id');
    }

    public function absen()
    {
      return  $this->hasMany(AbsensiLogs::class,'user_id');
    }

    public function absenDesc()
    {
      return  $this->hasMany(AbsensiDesc::class,'user_id');
    }

    public function absenRuang()
    {
      return $this->belongsToMany(Ruang::class,'absensi_log','user_id','ruang_id')->withTimestamps();
    }
}
