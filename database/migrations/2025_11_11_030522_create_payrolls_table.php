<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('divisi_id')->nullable();

            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->text('keterangan')->nullable();

            $table->string('rekening')->nullable();
            $table->date('tanggal_gabung')->nullable();

            $table->string('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->string('periode')->nullable();
            $table->string('nomor_gaji')->nullable();

            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('uang_transport', 15, 2)->default(0);
            $table->decimal('reimbursement', 15, 2)->default(0);

            $table->integer('lembur')->default(0);
            $table->decimal('uang_lembur', 15, 2)->default(0);

            $table->decimal('bonus_pribadi', 15, 2)->default(0);
            $table->decimal('bonus_team', 15, 2)->default(0);
            $table->decimal('bonus_jackpot', 15, 2)->default(0);

            $table->integer('kehadiran_100')->default(0);
            $table->decimal('bonus_kehadiran', 15, 2)->default(0);

            $table->decimal('tunjangan_bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('tunjangan_bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('tunjangan_pajak', 15, 2)->default(0);

            $table->integer('thr')->default(0);
            $table->decimal('tunjangan_hari_raya', 15, 2)->default(0);

            $table->decimal('total_tambah', 15, 2)->default(0);

            $table->integer('mangkir')->default(0);
            $table->decimal('uang_mangkir', 15, 2)->default(0);

            $table->integer('izin')->default(0);
            $table->decimal('uang_izin', 15, 2)->default(0);

            $table->integer('terlambat')->default(0);
            $table->decimal('uang_terlambat', 15, 2)->default(0);

            $table->decimal('bayar_kasbon', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('loss', 15, 2)->default(0);

            $table->decimal('total_pengurangan', 15, 2)->default(0);
            $table->decimal('gaji_diterima', 15, 2)->default(0);
            $table->timestamps();

            $table->index(['company_id', 'pegawai_id']);
            $table->index(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
