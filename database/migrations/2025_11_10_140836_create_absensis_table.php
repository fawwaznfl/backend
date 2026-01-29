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

            // === RELASI UTAMA
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('lokasi_id')
                ->nullable()
                ->constrained('lokasis')
                ->nullOnDelete();

            $table->foreignId('shift_id')
                ->nullable()
                ->constrained('shifts')
                ->nullOnDelete();

            // === DATA WAKTU ABSENSI
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            // === KETERLAMBATAN
            $table->integer('telat')->nullable();          // menit
            $table->integer('pulang_cepat')->nullable();   // menit

            // === LOKASI ABSENSI
            $table->string('lokasi_masuk')->nullable();
            $table->string('lokasi_pulang')->nullable();

            // === FOTO ABSENSI
            $table->string('foto_masuk')->nullable();
            $table->string('foto_pulang')->nullable();

            // === KETERANGAN TERPISAH
            $table->text('keterangan_masuk')->nullable();
            $table->text('keterangan_pulang')->nullable();

            // === KETERANGAN UMUM
            $table->text('keterangan')->nullable();

            // === KOORDINAT GPS
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // === STATUS ABSENSI
            $table->enum('status', [
                'hadir',
                'sakit',
                'izin',
                'cuti',
                'dinas_luar',
                'libur',
                'alpha',
            ])->default('alpha');

            // === VERIFIKASI ADMIN
            $table->enum('verifikasi', [
                'pending',
                'disetujui',
                'ditolak',
            ])->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            // === FACE RECOGNITION
            $table->decimal('face_score', 5, 4)->nullable();
            $table->boolean('face_verified')->default(false);

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
