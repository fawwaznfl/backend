<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('face_embeddings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pegawai_id')->unique();

            // InsightFace embedding (512 float32 → BLOB)
            $table->binary('embedding');

            $table->timestamps();

            // Optional FK (kalau tabel pegawais ada)
            $table->foreign('pegawai_id')
                  ->references('id')
                  ->on('pegawais')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_embeddings');
    }
};
