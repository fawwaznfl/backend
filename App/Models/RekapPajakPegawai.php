<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPajakPegawai extends Model
{
    use HasFactory;

    protected $table = 'rekap_pajak_pegawai';

    protected $fillable = [
        'company_id',
        'pegawai_id',
        'payroll_id',
        'tarif_pph_id',
        'bulan',
        'tahun',
        'penghasilan_bruto',
        'penghasilan_netto',
        'ptkp',
        'pkp',
        'tarif_persen',
        'pajak_terutang',
        'pajak_dipotong',
        'pajak_selisih',
        'tanggal_proses',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function tarifPph()
    {
        return $this->belongsTo(TarifPph::class);
    }
}
