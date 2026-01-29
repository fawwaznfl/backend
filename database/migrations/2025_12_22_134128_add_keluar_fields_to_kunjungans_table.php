<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->string('foto_keluar')->nullable()->after('upload_foto');
            $table->text('keterangan_keluar')->nullable()->after('keterangan');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropColumn(['foto_keluar', 'keterangan_keluar']);
        });
    }

};
