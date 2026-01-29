<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            // === Foreign key opsional
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // === Data utama shift
            $table->string('nama');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            // === Audit trail
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('shifts');
        Schema::enableForeignKeyConstraints();
    }
};
