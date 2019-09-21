<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSiswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('nisn')->nullable();
            $table->string('nis');
            $table->string('kelas')->nullable();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->tinyInteger('jenis_kelamin')->default(1);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->default('islam');
            $table->string('kewarganegaraan')->default('WNI');
            $table->smallInteger('anak_ke')->nullable();
            $table->smallInteger('jumlah_saudara')->nullable();
            $table->string('bahasa_hari')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telp')->nullable();

            $table->string('golda',30)->nullable();
            $table->smallInteger('tinggi')->nullable();
            $table->smallInteger('berat')->nullable();
            $table->string('kelainan_jasmani')->nullable();

            $table->string('nama_ayah')->nullable();
            $table->string('alamat_ayah')->nullable();
            $table->string('telp_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('alamat_ibu')->nullable();
            $table->string('telp_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('alamat_wali')->nullable();
            $table->string('telp_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();

            $table->string('asal_sekolah')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->date('tanggal_tamat')->nullable();
            $table->integer('nomor_ijazah')->nullable();
            $table->date('tanggal_ijazah')->nullable();
            $table->string('pendidikan_lanjut')->nullable();

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
        Schema::dropIfExists('siswa');
    }
}
