<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration untuk membuat tabel lemburs versi terbaru.
     */
    public function up(): void
    {
        Schema::create('lemburs', function (Blueprint $table) {
            $table->id();

            // Relasi ke perusahaan
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // Relasi ke pegawai
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('cascade');

            // Informasi lembur
            $table->date('tanggal_lembur');

            // Waktu lembur
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();

            // Lokasi & Foto Masuk
            $table->string('lokasi_masuk')->nullable();
            $table->string('foto_masuk')->nullable();

            // Lokasi & Foto Pulang
            $table->string('lokasi_pulang')->nullable();
            $table->string('foto_pulang')->nullable();

            // Total Jam Lembur (hasil hitung akhir)
            $table->decimal('total_lembur', 5, 2)->nullable();

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            // Status persetujuan
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');

            // Audit
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('lemburs');
    }
};
