<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_pengajuan_keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');

            // 🔹 sumber pengajuan (kasbon / reimbursement)
            $table->enum('tipe', ['kasbon', 'reimbursement']);
            $table->unsignedBigInteger('sumber_id'); // id dari tabel sumber
            $table->date('tanggal_pengajuan');

            // 🔹 informasi umum
            $table->string('keterangan')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_pengajuan_keuangans');
    }
};
