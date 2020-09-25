<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnNuptk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->bigInteger('nuptk')->after('nama')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('urutan')->after('active')->default(9999);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn('nuptk');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
}
