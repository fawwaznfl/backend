<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->enum('tipe', ['informasi', 'berita']);
            $table->string('judul');
            $table->text('isi_konten')->nullable();
            $table->string('gambar')->nullable();
            $table->string('upload_file')->nullable();
            $table->date('tanggal_publikasi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
