<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel patrolis.
     */
    public function up(): void
    {
        Schema::create('patrolis', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi ke perusahaan
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // 🔹 Relasi ke pegawai (pelaksana patroli)
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');

            // 🔹 Informasi utama patroli
            $table->string('lokasi')->nullable();
            $table->string('tujuan');
            $table->text('keterangan')->nullable();

            // 🔹 Waktu pelaksanaan
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();

            // 🔹 Dokumentasi dan status
            $table->string('bukti_patrol')->nullable(); // upload foto atau bukti patroli
            $table->enum('status', ['berlangsung', 'selesai', 'batal'])->default('berlangsung');

            // 🔹 Catatan tambahan
            $table->text('catatan')->nullable();

            // 🔹 Audit trail
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Rollback migration (hapus tabel patrolis).
     */
    public function down(): void
    {
        Schema::dropIfExists('patrolis');
    }
};
