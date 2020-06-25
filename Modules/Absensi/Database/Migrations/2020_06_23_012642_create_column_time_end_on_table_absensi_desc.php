<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnTimeEndOnTableAbsensiDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absensi_desc', function (Blueprint $table) {
            $table->datetime('time_end')->nullable()->after('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absensi_desc', function (Blueprint $table) {
            $table->dropColumn('time_desc');
        });
    }
}
