<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dinas_luars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();

            $table->string('tujuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal'); 

            $table->enum('status', [
                'hadir',
                'sakit',
                'izin',
                'cuti',
                'dinas_luar',
                'libur',
                'alpha'
            ])->default('dinas_luar');

            $table->enum('verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->time('jam_masuk')->nullable();
            $table->string('telat')->nullable();
            $table->string('lokasi_masuk')->nullable();
            $table->string('foto_masuk')->nullable();

            $table->time('jam_pulang')->nullable();
            $table->string('pulang_cepat')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->string('lokasi_pulang')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dinas_luars');
    }
};
