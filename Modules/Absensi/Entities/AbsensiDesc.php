<?php

namespace Modules\Absensi\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;

class AbsensiDesc extends Model
{
    protected $table = 'absensi_desc';

    protected $dates = [
      'time',
      'time_end',
    ];

    protected $casts = [
      'jadwal' => 'array'
    ];

    public function user()
    {
      return $this->belongsTo(User::class,'user_id');
    }

    public function setTimeAttribute($value)
    {
      $this->attributes['time'] = $value?Carbon::createFromFormat('Y/m/d',$value)->format('Y-m-d'):null;
    }
    public function setTimeEndAttribute($value)
    {
      $this->attributes['time_end'] = $value?Carbon::createFromFormat('Y/m/d',$value)->format('Y-m-d'):null;
    }

}
