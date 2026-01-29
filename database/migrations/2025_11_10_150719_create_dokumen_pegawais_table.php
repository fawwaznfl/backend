<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenPegawaisTable extends Migration
{
    public function up()
    {
        Schema::create('dokumen_pegawais', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->onDelete('cascade');

            $table->foreignId('company_id')
                ->constrained('companies')
                ->onDelete('cascade');

            $table->string('nama_dokumen');
            $table->string('file'); 
            $table->text('keterangan')->nullable();
            $table->dateTime('tanggal_upload')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_pegawais');
    }
}
