<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai_keluars', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();

            $table->string('jenis_keberhentian')->nullable();
            $table->string('alasan')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->string('upload_file')->nullable();

            $table->string('status')->default('pending');
            $table->text('note_approver')->nullable();

            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index('company_id');
            $table->index('pegawai_id');
            $table->index('disetujui_oleh');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai_keluars');
    }
};
