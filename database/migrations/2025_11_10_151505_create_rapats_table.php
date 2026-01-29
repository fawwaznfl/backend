<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapats', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi utama
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // 🔹 Informasi rapat
            $table->string('judul');
            $table->text('nama_pertemuan')->nullable();
            $table->date('tanggal_rapat')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->enum('jenis_pertemuan', ['online', 'offline'])->default('offline');
            $table->string('lokasi')->nullable(); 
            $table->string('notulen')->nullable(); 
            $table->string('file_notulen')->nullable(); 
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapats');
    }
};
