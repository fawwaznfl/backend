<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {

            // tahun sebelum bulan
            if (!Schema::hasColumn('payrolls', 'tahun')) {
                $table->string('tahun')->nullable()->before('bulan');
            }

            // 100_kehadiran sebelum bonus_kehadiran
            if (!Schema::hasColumn('payrolls', '100_kehadiran')) {
                $table->integer('100_kehadiran')
                      ->default(0)
                      ->before('bonus_kehadiran');
            }

            // thr sebelum tunjangan_hari_raya
            if (!Schema::hasColumn('payrolls', 'thr')) {
                $table->integer('thr')
                      ->default(0)
                      ->before('tunjangan_hari_raya');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['tahun', '100_kehadiran', 'thr']);
        });
    }
};
