<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai_keluars', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi utama
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('cascade');

            // 🔹 Data pegawai keluar
            $table->date('tanggal_keluar')->nullable();
            $table->string('alasan')->nullable();
            $table->enum('jenis_keberhentian', [
            'PHK',
            'Pengunduran Diri',
            'Meninggal Dunia',
            'Pensiun',
            ])->nullable();
            
            $table->string('upload_file')->nullable(); // 🔁 ganti dari keterangan jadi upload_file

            // 🔹 Audit
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai_keluars');
    }
};
