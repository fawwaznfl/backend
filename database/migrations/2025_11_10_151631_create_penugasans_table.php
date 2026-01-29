<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penugasans', function (Blueprint $table) {

            // 🔹 Kolom penugasan
            if (!Schema::hasColumn('penugasans', 'judul_pekerjaan')) {
                $table->string('judul_pekerjaan')->after('pegawai_id');
            }

            if (!Schema::hasColumn('penugasans', 'rincian_pekerjaan')) {
                $table->text('rincian_pekerjaan')->nullable()->after('judul_pekerjaan');
            }

            if (!Schema::hasColumn('penugasans', 'nomor_penugasan')) {
                $table->string('nomor_penugasan')->nullable()->after('id');
            }

        });
    }

    public function down(): void
    {
        Schema::table('penugasans', function (Blueprint $table) {
            if (Schema::hasColumn('penugasans', 'judul_pekerjaan')) {
                $table->dropColumn('judul_pekerjaan');
            }

            if (Schema::hasColumn('penugasans', 'rincian_pekerjaan')) {
                $table->dropColumn('rincian_pekerjaan');
            }

            if (Schema::hasColumn('penugasans', 'nomor_penugasan')) {
                $table->dropColumn('nomor_penugasan');
            }
        });
    }
};
