<?php

namespace Modules\Absensi\Entities;

use Illuminate\Database\Eloquent\Model;

class AbsensiLogs extends Model
{
    protected $table = 'absensi_log';

    protected $dates = [
      'created_at',
      'updated_at',
    ];
}
