<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dinas_luars', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi umum
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();

            // 🔹 Info dinas luar
            $table->string('tujuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('approved_by')->nullable()->constrained('pegawais')->nullOnDelete();

            // 🔹 Absensi selama dinas luar
            $table->date('tanggal')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->string('telat')->nullable();
            $table->decimal('lokasi_masuk', 10, 7)->nullable();
            $table->string('foto_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('pulang_cepat')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->decimal('lokasi_pulang', 10, 7)->nullable();                       
            $table->string('status_absen')->nullable();

            // 🔹 Audit
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('dinas_luars');
        Schema::enableForeignKeyConstraints();
    }
};
