<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePegawai extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('pegawai', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->uuid('uuid');
      $table->string('nama');
      $table->bigInteger('nip')->nullable();
      $table->string('tempat_lahir')->nullable();
      $table->date('tanggal_lahir')->nullable();
      $table->tinyInteger('jenis_kelamin')->default(1);
      $table->string('agama')->default('islam');
      $table->string('status_kawin')->default('belum');
      $table->string('alamat')->nullable();
      $table->string('jabatan')->nullable();
      $table->string('status_kepegawaian')->nullable();
      $table->string('pangkat_golongan')->nullable();
      $table->string('unit_kerja')->nullable();
      $table->date('mulai_masuk')->nullable();

      $table->string('golda')->nullable();
      $table->smallInteger('tinggi')->nullable();
      $table->smallInteger('berat')->nullable();
      $table->string('rambut')->nullable();
      $table->string('bentuk_muka')->nullable();
      $table->string('warna_muka')->nullable();
      $table->string('ciri_ciri')->nullable();
      $table->string('kegemaran')->nullable();

      $table->string('pendidikan_akhir')->nullable();
      $table->text('riwayat_pendidikan')->nullable();

      $table->string('foto')->nullable();
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
    Schema::dropIfExists('pegawai');
  }
}
