<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnMenitPerJamAndSatuanJam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absensi_jadwal', function (Blueprint $table) {
            $table->mediumInteger('menit_per_jam')->after('early')->default(60);
            $table->string('satuan_jam',100)->after('menit_per_jam')->default('Jam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('');
    }
}
