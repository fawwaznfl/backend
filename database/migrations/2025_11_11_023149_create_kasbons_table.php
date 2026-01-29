<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');

            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->onDelete('cascade');

            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->string('keperluan')->nullable();

            $table->enum('metode_pengiriman', ['cash', 'transfer'])
                ->default('cash');

            $table->string('nomor_rekening')->nullable();

            $table->enum('status', ['pending', 'approve', 'reject', 'paid'])
                ->default('pending');

            $table->string('file_approve')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasbons');
    }
};
