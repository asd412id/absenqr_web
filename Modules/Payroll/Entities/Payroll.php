<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Modules\Absensi\Entities\Jadwal;

class Payroll extends Model
{
    protected $fillable = [
      "uuid",
      "user_id",
      "name",
      "gaji",
      "menit",
      "jadwal_id",
    ];

    protected $table = 'payroll';

    protected $casts = [
      "jadwal_id" => "array"
    ];

    public function user()
    {
      return $this->belongsTo(User::class,'user_id');
    }

    public function getJadwalAttribute()
    {
      return Jadwal::whereIn('id',$this->jadwal_id);
    }
}
