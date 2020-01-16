<?php

namespace Modules\Absensi\Entities;

use Illuminate\Database\Eloquent\Model;

use Modules\Absensi\Entities\Ruang;
use App\User;

class AbsensiLogs extends Model
{
    protected $table = 'absensi_log';

    protected $dates = [
      'created_at',
      'updated_at',
    ];

    public function ruang()
    {
      return $this->belongsTo(Ruang::class,'ruang_id');
    }

    public function user()
    {
      return $this->belongsTo(User::class,'user_id');
    }
}
