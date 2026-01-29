<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapat_pegawai', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rapat_id')
                ->constrained('rapats')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();

            $table->timestamp('waktu_hadir')->nullable();

            $table->timestamps();

            $table->unique(['rapat_id', 'pegawai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapat_pegawai');
    }
};
