<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'pegawai_id',
        'divisi_id',
        'rekening',
        'tanggal_gabung',
        'bulan',
        'tahun',
        'periode',
        'nomor_gaji',
        'gaji_pokok',
        'uang_transport',
        'lembur',
        'kehadiran_100',
        'bonus_kehadiran',
        'bonus_pribadi',
        'bonus_team',
        'uang_lembur',
        'bonus_jackpot',
        'tunjangan_hari_raya',
        'reimbursement',
        'tunjangan_bpjs_kesehatan',
        'tunjangan_bpjs_ketenagakerjaan',
        'tanggal_gabung',
        'tunjangan_pajak',
        'total_tambah',
        'terlambat',
        'uang_terlambat',
        'mangkir',
        'uang_mangkir',
        'izin',
        'uang_izin',
        'bayar_kasbon',
        'potongan_bpjs_kesehatan',
        'potongan_bpjs_ketenagakerjaan',
        'loss',
        'total_pengurangan',
        'gaji_diterima',
        'status',
        'thr',
        'keterangan',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function rekapPajak()
    {
        return $this->belongsTo(RekapPajakPegawai::class, 'rekap_daftar_pajak_pegawai_id');
    }
}
