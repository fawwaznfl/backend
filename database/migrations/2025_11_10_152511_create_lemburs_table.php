<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lemburs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('cascade');

            $table->date('tanggal_lembur');

            $table->string('lokasi_masuk')->nullable();
            $table->string('foto_masuk')->nullable();

            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();

            $table->integer('total_lembur_menit')->nullable();

            $table->string('lokasi_pulang')->nullable();
            $table->string('foto_pulang')->nullable();

            $table->text('keterangan')->nullable();

            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');

            $table->foreignId('disetujui_oleh')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lemburs');
    }
};
