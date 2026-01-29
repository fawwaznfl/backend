<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('target_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('nomor_target')->unique(); // otomatis, format: TK0001
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('divisi_id')->nullable()->constrained('divisis')->onDelete('set null');

            $table->decimal('target_pribadi', 15, 2)->default(0);
            $table->decimal('jumlah_persen_pribadi', 5, 2)->default(0);
            $table->decimal('bonus_pribadi', 15, 2)->default(0);

            $table->decimal('target_team', 15, 2)->default(0);
            $table->decimal('jumlah_persen_team', 5, 2)->default(0);
            $table->decimal('bonus_team', 15, 2)->default(0);

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('jackpot', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_kinerjas');
    }
};
