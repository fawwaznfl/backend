<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('laporan_kerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');

            $table->text('informasi_umum');
            $table->text('pekerjaan_yang_dilaksanakan');
            $table->text('pekerjaan_belum_selesai');
            $table->text('catatan');
            $table->date('tanggal_laporan');

            $table->timestamps();

            $table->foreign('pegawai_id')
                ->references('id')
                ->on('pegawais')   // ✅ FIX DI SINI
                ->onDelete('cascade');
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerja');
    }
};
