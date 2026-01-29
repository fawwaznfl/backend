<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dinas_luars', function (Blueprint $table) {

            // === Status absensi (selaras absensis)
            $table->enum('status', [
                'hadir',
                'sakit',
                'izin',
                'cuti',
                'dinas_luar',
                'libur',
                'alpha',
            ])->default('dinas_luar')->after('tanggal');

            // === Status verifikasi admin
            $table->enum('verifikasi', [
                'pending',
                'disetujui',
                'ditolak'
            ])->default('pending')->after('status');

            // === Admin/Pegawai yang menyetujui
            $table->foreignId('approved_by')
                ->nullable()
                ->after('verifikasi')
                ->constrained('pegawais')
                ->nullOnDelete();

            // === Waktu verifikasi (opsional tapi recommended)
            $table->timestamp('verified_at')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('dinas_luars', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();

            $table->dropColumn([
                'status',
                'verifikasi',
                'verified_at'
            ]);

            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');

            Schema::enableForeignKeyConstraints();
        });
    }
};
