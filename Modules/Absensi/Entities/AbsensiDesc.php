<?php

namespace Modules\Absensi\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;

class AbsensiDesc extends Model
{
    protected $table = 'absensi_desc';

    protected $dates = [
      'time'
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
      $this->attributes['time'] = $value?Carbon::createFromFormat('d-m-Y',$value)->format('Y-m-d'):null;
    }

}
