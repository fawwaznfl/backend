<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_mapping', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawais')
                ->cascadeOnDelete();

            $table->foreignId('shift_id')
                ->constrained('shifts')
                ->cascadeOnDelete();

            $table->foreignId('shift_lama_id')
                ->nullable()
                ->constrained('shifts')
                ->nullOnDelete();

            $table->integer('toleransi_telat')->default(0);
            $table->string('status_toleransi')->nullable();

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->nullable()
                ->default('pending');

            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('pegawais')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->unique(['pegawai_id', 'tanggal_mulai']);
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('shift_mapping');
        Schema::enableForeignKeyConstraints();
    }
};
