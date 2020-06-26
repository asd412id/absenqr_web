<?php

namespace Modules\Absensi\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;

class HariLibur extends Model
{
    protected $table = 'absensi_libur';

    protected $dates = [
      'start',
      'end',
    ];

    public function setStartAttribute($value)
    {
      $this->attributes['start'] = $value?Carbon::createFromFormat('Y/m/d',$value)->format('Y-m-d'):null;
    }
    public function setEndAttribute($value)
    {
      $this->attributes['end'] = $value?Carbon::createFromFormat('Y/m/d',$value)->format('Y-m-d'):null;
    }
    public function getStartAttribute($value)
    {
      return Carbon::parse($value)->locale('id')->translatedFormat('j F Y');
    }
    public function getEndAttribute($value)
    {
      return Carbon::parse($value)->locale('id')->translatedFormat('j F Y');
    }
    public function getStartDataAttribute()
    {
      return Carbon::parse($this->attributes['start'])->format('Y/m/d');
    }
    public function getEndDataAttribute($value)
    {
      return Carbon::parse($this->attributes['end'])->format('Y/m/d');
    }

}
