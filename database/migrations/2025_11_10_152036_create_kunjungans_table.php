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

            // 🔹 Relasi utama
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');
            $table->foreignId('pegawai_id')
                ->nullable()
                ->constrained('pegawais')
                ->onDelete('cascade');

            // 🔹 Data kunjungan
            $table->string('upload_foto')->nullable(); 
            $table->text('keterangan')->nullable();    

            // 🔹 Audit
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
