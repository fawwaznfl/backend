<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pegawais')->insert([
            'id' => 1,
            'company_id' => null,
            'role_id' => null,
            'divisi_id' => null,
            'lokasi_id' => null,
            'shift_id' => null,

            'name' => 'SuperAdmin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'telepon' => '085175441047',
            'password' => '$2y$12$mpJgDmVMcvZ3ZUXWZQvF3ebtCFuv8BQX/8moFNfF5Iu5MdP7xQ9Nu',

            'email_verified_at' => null,
            'remember_token' => null,

            'foto_karyawan' => null,
            'foto_face_recognition' => null,
            'tgl_lahir' => null,
            'gender' => null,
            'tgl_join' => '2026-01-27',
            'status_nikah' => null,
            'alamat' => null,

            'ktp' => null,
            'kartu_keluarga' => null,
            'bpjs_kesehatan' => null,
            'bpjs_ketenagakerjaan' => null,
            'npwp' => null,
            'sim' => null,

            'no_pkwt' => null,
            'no_kontrak' => null,
            'tanggal_mulai_pwkt' => null,
            'tanggal_berakhir_pwkt' => null,
            'masa_berlaku' => null,

            'rekening' => null,
            'nama_rekening' => null,

            'gaji_pokok' => 0.00,
            'makan_transport' => 0.00,
            'lembur' => 0.00,
            'kehadiran' => 0.00,
            'thr' => 0.00,
            'bonus_pribadi' => 0.00,
            'bonus_team' => 0.00,
            'bonus_jackpot' => 0.00,
            'izin' => 0.00,
            'terlambat' => 0.00,
            'mangkir' => 0,
            'saldo_kasbon' => 0.00,

            'status_pajak' => null,

            'tunjangan_bpjs_kesehatan' => 0.00,
            'potongan_bpjs_kesehatan' => 0.00,
            'tunjangan_bpjs_ketenagakerjaan' => 0.00,
            'potongan_bpjs_ketenagakerjaan' => 0.00,
            'tunjangan_pajak' => 0.00,

            'izin_cuti' => 0,
            'izin_lainnya' => 0,
            'izin_telat' => 0,
            'izin_pulang_cepat' => 0,

            'status' => 'active',
            'dashboard_type' => 'superadmin',

            'created_at' => Carbon::parse('2026-01-27 11:20:38'),
            'updated_at' => Carbon::parse('2026-01-27 11:20:38'),
        ]);
    }
}
