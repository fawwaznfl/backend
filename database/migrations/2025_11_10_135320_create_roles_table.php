<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            // === Foreign key opsional (dibuat nullable dulu, FK ditambah setelah tabel lain siap)
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            
            $table->string('nama');
            $table->text('deskripsi')->nullable();

            // === Audit trail
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });

        // === Tabel pivot untuk relasi pegawai <-> role (bisa banyak role)
        Schema::create('pegawai_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps();

            // Supaya 1 pegawai gak punya role yang sama dua kali
            $table->unique(['pegawai_id', 'role_id']);
        });
    }

    public function down(): void
    {
        // Pastikan pivot dihapus dulu supaya gak ada FK error
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pegawai_role');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();
    }
};
