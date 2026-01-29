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
                ->onDelete('cascade');

            $table->enum('tipe', ['informasi', 'berita']);
            $table->string('judul');
            $table->text('isi_konten')->nullable();
            $table->string('gambar')->nullable();
            $table->string('upload_file')->nullable(); // file tambahan (opsional)

            // Kolom baru
            $table->date('tanggal_publikasi')->nullable()->after('upload_file');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
