<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            // === Relasi utama
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();

            // === Data absensi otomatis oleh sistem
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            // === Data foto absensi
            $table->string('foto_masuk')->nullable();   // FOTO MASUK
            $table->string('foto_pulang')->nullable();  // FOTO PULANG (tambahan)

            // === Data lokasi absensi
            $table->string('lokasi_masuk')->nullable();   // LOKASI MASUK
            $table->string('lokasi_pulang')->nullable();  // LOKASI PULANG

            // === Perhitungan telat / pulang cepat
            $table->integer('telat')->nullable();          // menit
            $table->integer('pulang_cepat')->nullable();   // menit

            // === Keterangan opsional (gabungan)
            $table->text('keterangan')->nullable();

            // === Koordinat otomatis
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // === Status absensi
            $table->enum('status', [
                'hadir',
                'sakit',
                'izin',
                'cuti',
                'dinas_luar',
                'libur',
                'alpha',
            ])->default('hadir');

            // === Verifikasi oleh admin
            $table->enum('verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('absensis');
        Schema::enableForeignKeyConstraints();
    }
};
