<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');

            $table->foreignId('pegawai_id')
                ->nullable()
                ->constrained('pegawais')
                ->onDelete('cascade');

            $table->string('upload_foto')->nullable();
            $table->string('lokasi_masuk')->nullable();
            $table->text('keterangan')->nullable();

            $table->string('foto_keluar')->nullable();
            $table->string('lokasi_keluar')->nullable();
            $table->text('keterangan_keluar')->nullable();

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
        Schema::dropIfExists('kunjungans');
    }
};
