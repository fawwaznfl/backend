<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel cutis.
     */
    public function up(): void
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi utama
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('cascade');

            // 🔹 Informasi cuti
            $table->enum('jenis_cuti', [
                'tahunan',
                'sakit',
                'melahirkan',
                'penting',
                'lainnya'
            ])->default('tahunan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari')->nullable();
            $table->text('alasan')->nullable();

            // 🔹 Bukti pendukung
            $table->string('lampiran')->nullable(); // upload file surat dokter, surat izin, dsb

            // 🔹 Status persetujuan
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pegawais')->nullOnDelete();

            // 🔹 Audit trail
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Rollback migration (hapus tabel cutis).
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
