<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel kasbons.
     */
    public function up(): void
    {
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi ke perusahaan
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');

            // 🔹 Relasi ke pegawai
            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->onDelete('cascade');

            // 🔹 Informasi utama kasbon
            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->string('keperluan')->nullable();

            // 🔹 Metode pengiriman kasbon
            $table->enum('metode_pengiriman', ['cash', 'transfer'])
                ->default('cash');

            // 🔹 Nomor rekening (wajib jika transfer)
            $table->string('nomor_rekening')->nullable();

            // 🔹 Status kasbon
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dibayar'])
                ->default('menunggu');

            // 🔹 File approve (jika kasbon disetujui)
            $table->string('file_approve')->nullable();

            // 🔹 Tanggal pelunasan
            $table->date('tanggal_pelunasan')->nullable();

            // 🔹 Audit
            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbons');
    }
};
