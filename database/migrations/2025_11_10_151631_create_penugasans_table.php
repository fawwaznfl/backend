<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_penugasan')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();

            $table->string('judul_pekerjaan');
            $table->text('rincian_pekerjaan')->nullable();

            $table->enum('status', ['process', 'pending', 'finish'])
                  ->default('process');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index('company_id');
            $table->index('pegawai_id');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
