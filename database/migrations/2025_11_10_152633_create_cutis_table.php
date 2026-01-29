<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->nullable()
                ->constrained('pegawais')
                ->cascadeOnDelete();

            $table->enum('jenis_cuti', [
                'cuti',
                'izin_masuk',
                'izin_telat',
                'izin_pulang_cepat',
                'sakit',
                'melahirkan',
            ])->default('cuti');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->text('alasan')->nullable();
            $table->text('catatan')->nullable();

            $table->string('foto')->nullable();
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
            ])->default('menunggu');

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

    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
