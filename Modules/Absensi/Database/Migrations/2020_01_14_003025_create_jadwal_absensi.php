<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalAbsensi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi_jadwal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('nama_jadwal');
            $table->bigInteger('ruang');
            $table->json('hari');
            $table->time('cin');
            $table->time('cout');
            $table->time('start_cin');
            $table->time('end_cin');
            $table->time('start_cout');
            $table->time('end_cout');
            $table->smallInteger('late')->default(0);
            $table->smallInteger('early')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi_jadwal');
    }
}
