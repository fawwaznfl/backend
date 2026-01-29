<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shift_mapping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('shift_id');

            // Update: tanggal_mulai & tanggal_selesai
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');

            // Unique (pegawai hanya punya 1 shift range pada tanggal yg sama)
            $table->unique(['pegawai_id', 'tanggal_mulai']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shift_mapping');
    }
};
