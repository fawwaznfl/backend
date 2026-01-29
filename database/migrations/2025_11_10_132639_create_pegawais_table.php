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

            // === Hubungan Utama ===
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');

            // === Foreign key opsional (dibuat nullable dulu, FK ditambah setelah tabel lain siap)
            $table->unsignedBigInteger('role_id')->nullable();   // <-- ini tempat role
            $table->unsignedBigInteger('divisi_id')->nullable();
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();

            // === Data Akun ===
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('telepon')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            // === Data Pribadi ===
            $table->string('foto_karyawan')->nullable();
            $table->string('foto_face_recognition')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('tgl_join')->nullable(); 
            $table->enum('status_nikah', ['TK/0', 'TK/1', 'TK/2', 'TK/3','K0', 'K1', 'K2', 'K3'])->nullable();
            $table->text('alamat')->nullable();

            // === Dokumen Pribadi ===
            $table->string('ktp')->nullable();
            $table->string('kartu_keluarga')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('bpjs_ketenagakerjaan')->nullable();
            $table->string('npwp')->nullable();
            $table->string('sim')->nullable();

            // === Data Kontrak ===
            $table->string('no_pkwt')->nullable();
            $table->string('no_kontrak')->nullable();
            $table->date('tanggal_mulai_pwkt')->nullable();
            $table->date('tanggal_berakhir_pwkt')->nullable();
            $table->date('masa_berlaku')->nullable();

            // === Data Rekening & Gaji ===
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
            $table->decimal('mangkir', 15, 2)->default(0);
            $table->decimal('saldo_kasbon', 15, 2)->default(0);

            // === Tunjangan & Pajak ===
            $table->enum('status_pajak', ['TK/0', 'TK/1', 'TK/2', 'TK/3','K0', 'K1', 'K2', 'K3'])->nullable();
            $table->decimal('tunjangan_bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('tunjangan_bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('tunjangan_pajak', 15, 2)->default(0);

            // === Izin-izin ===
            $table->integer('izin_cuti')->default(0);
            $table->integer('izin_lainnya')->default(0);
            $table->integer('izin_telat')->default(0);
            $table->integer('izin_pulang_cepat')->default(0);

            // === Status Umum ===
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('dashboard_type', ['superadmin', 'admin', 'pegawai'])->default('pegawai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
