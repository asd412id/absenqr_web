<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
  protected $table = 'configs';

  protected $fillable = ['config','value'];
  public $timestamps = false;

  public static function getAll()
  {
    $configs = self::select('value','config')
    ->get()
    ->pluck('value','config')
    ->toArray();

    return (object) $configs;
  }
}
