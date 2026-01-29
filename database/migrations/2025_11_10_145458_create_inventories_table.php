<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');

            $table->foreignId('lokasi_id')
                ->nullable()
                ->constrained('lokasis')
                ->nullOnDelete();

            $table->foreignId('divisi_id')
                ->nullable()
                ->constrained('divisis')
                ->nullOnDelete();

            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->integer('stok')->default(0);
            $table->string('satuan')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status', ['tersedia', 'rusak', 'hilang'])->default('tersedia');
            $table->text('keterangan')->nullable();

            // 🔹 Audit trail
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('inventories');
        Schema::enableForeignKeyConstraints();
    }
};
