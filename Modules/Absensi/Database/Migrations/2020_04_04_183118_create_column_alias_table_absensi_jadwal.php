<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnAliasTableAbsensiJadwal extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('absensi_jadwal', function (Blueprint $table) {
      $table->string('alias')->after('nama_jadwal')->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('absensi_jadwal', function (Blueprint $table) {
      $table->dropColumn('alias');
    });
  }
}
