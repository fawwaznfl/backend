<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();

            // === Relasi perusahaan
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // === Data lokasi
            $table->string('nama_lokasi');
            $table->string('lat_kantor')->nullable();
            $table->string('long_kantor')->nullable();
            $table->integer('radius')->default(100); // meter
            $table->text('keterangan')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // === Audit
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lokasis');
        Schema::enableForeignKeyConstraints();
    }
};
