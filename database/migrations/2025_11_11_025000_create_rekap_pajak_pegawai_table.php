<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_pajak_pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->foreignId('tarif_pph_id')->nullable()->constrained('tarif_pphs')->onDelete('set null');

            $table->string('bulan', 20)->nullable();
            $table->year('tahun')->nullable();

            $table->decimal('penghasilan_bruto', 15, 2)->default(0);
            $table->decimal('penghasilan_netto', 15, 2)->default(0);
            $table->decimal('ptkp', 15, 2)->default(0)->comment('Penghasilan Tidak Kena Pajak');
            $table->decimal('pkp', 15, 2)->default(0)->comment('Penghasilan Kena Pajak');
            $table->decimal('tarif_persen', 5, 2)->default(0);
            $table->decimal('pajak_terutang', 15, 2)->default(0);
            $table->decimal('pajak_dipotong', 15, 2)->default(0);
            $table->decimal('pajak_selisih', 15, 2)->default(0)->comment('Selisih antara pajak terutang dan dipotong');
            
            $table->date('tanggal_proses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('rekap_pajak_pegawai', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->dropForeign(['tarif_pph_id']);
        });

        Schema::dropIfExists('rekap_pajak_pegawai');
    }

};
