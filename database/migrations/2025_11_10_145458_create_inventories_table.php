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

            // 🔹 Relasi umum
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // 🔹 Informasi Barang
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->string('kategori')->nullable();
            $table->integer('jumlah')->default(0);
            $table->string('satuan')->nullable(); // contoh: unit, pcs, box, dll
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->enum('status', ['tersedia', 'rusak', 'hilang'])->default('tersedia');
            $table->text('keterangan')->nullable();

            // 🔹 Audit
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
