<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('divisi_id')->nullable();
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();

            // DATA AKUN 
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('telepon')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            // FOTO & DATA PRIBADI
            $table->string('foto_karyawan')->nullable();
            $table->string('foto_face_recognition')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('tgl_join')->nullable();
            $table->enum('status_nikah', [
                'TK/0','TK/1','TK/2','TK/3',
                'K0','K1','K2','K3'
            ])->nullable();
            $table->text('alamat')->nullable();

            // DOKUMEN
            $table->string('ktp')->nullable();
            $table->string('kartu_keluarga')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('bpjs_ketenagakerjaan')->nullable();
            $table->string('npwp')->nullable();
            $table->string('sim')->nullable();

            // DATA KONTRAK
            $table->string('no_pkwt')->nullable();
            $table->string('no_kontrak')->nullable();
            $table->date('tanggal_mulai_pwkt')->nullable();
            $table->date('tanggal_berakhir_pwkt')->nullable();
            $table->date('masa_berlaku')->nullable();

            // REKENING & GAJI
            $table->string('rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('makan_transport', 15, 2)->default(0);
            $table->decimal('lembur', 15, 2)->default(0);
            $table->decimal('kehadiran', 15, 2)->default(0);
            $table->decimal('thr', 15, 2)->default(0);
            $table->decimal('bonus_pribadi', 15, 2)->default(0);
            $table->decimal('bonus_team', 15, 2)->default(0);
            $table->decimal('bonus_jackpot', 15, 2)->default(0);
            $table->decimal('izin', 15, 2)->default(0);
            $table->decimal('terlambat', 15, 2)->default(0);
            $table->integer('mangkir')->default(0);
            $table->decimal('saldo_kasbon', 15, 2)->default(0);

            // PAJAK & BPJS
            $table->enum('status_pajak', [
                'TK/0','TK/1','TK/2','TK/3',
                'K0','K1','K2','K3'
            ])->nullable();
            $table->decimal('tunjangan_bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('tunjangan_bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('tunjangan_pajak', 15, 2)->default(0);

            // IZIN
            $table->integer('izin_cuti')->default(0);
            $table->integer('izin_lainnya')->default(0);
            $table->integer('izin_telat')->default(0);
            $table->integer('izin_pulang_cepat')->default(0);

            // STATUS 
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('dashboard_type', ['superadmin', 'admin', 'pegawai'])->default('pegawai');
            $table->enum('kasbon_periode', ['bulan', 'tahun'])->default('bulan');
            $table->enum('terlambat_satuan', ['hari', 'jam', 'menit'])
              ->default('hari')
              ->after('terlambat');


            $table->timestamps();

            // INDEX 
            $table->index('company_id');
            $table->index('role_id');
            $table->index('divisi_id');
            $table->index('lokasi_id');
            $table->index('shift_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
