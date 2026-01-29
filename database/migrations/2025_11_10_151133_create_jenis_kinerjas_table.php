<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kinerjas', function (Blueprint $table) {
            $table->id();

            // Relasi utama
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // Data jenis kinerja
            $table->string('nama');
            $table->text('detail')->nullable();
            $table->decimal('bobot_penilaian', 5, 2)->default(0); 

            //  Audit
            $table->foreignId('created_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('pegawais')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kinerjas');
    }
};
