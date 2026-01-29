<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_pphs', function (Blueprint $table) {
            $table->id();
            $table->decimal('batas_bawah', 15, 2)->nullable(); // contoh: 0
            $table->decimal('batas_atas', 15, 2)->nullable();  // contoh: 50,000,000
            $table->decimal('tarif', 5, 2)->comment('Persentase PPh, contoh: 5.00 untuk 5%');
            $table->year('tahun')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_pphs');
    }
};
