<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->string('lokasi_masuk')->nullable()->after('upload_foto');
            $table->string('lokasi_keluar')->nullable()->after('foto_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropColumn(['lokasi_masuk', 'lokasi_keluar']);
        });
    }
};
