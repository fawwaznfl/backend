<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi umum
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('cascade');

            // 🔹 Info kontrak kerja
            $table->string('nomor_kontrak')->nullable();
            $table->enum('jenis_kontrak', [
                'PKWT',   // Perjanjian Kerja Waktu Tertentu
                'PKWTT',  // Perjanjian Kerja Waktu Tidak Tertentu
                'THL'     // Tenaga Harian Lepas
            ])->default('PKWT');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('file_kontrak')->nullable(); // dokumen kontrak kerja

            // 🔹 Audit
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontraks');
    }
};
