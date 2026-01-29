<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');

            $table->foreignId('pegawai_id')
                ->nullable()
                ->constrained('pegawais')
                ->onDelete('cascade');

            $table->string('nomor_kontrak')->nullable();
            $table->enum('jenis_kontrak', ['PKWT', 'PKWTT', 'THL'])
                ->default('PKWT');

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('file_kontrak')->nullable();

            $table->boolean('notified_h30')->default(false);
            $table->boolean('notified_h7')->default(false);

            $table->text('keterangan')->nullable();

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
        Schema::dropIfExists('kontraks');
    }
};
