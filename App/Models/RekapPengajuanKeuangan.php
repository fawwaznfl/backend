<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPengajuanKeuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'pegawai_id',
        'tipe',
        'sumber_id',
        'tanggal_pengajuan',
        'keterangan',
        'nominal',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function sumber()
    {
        return $this->morphTo(__FUNCTION__, 'tipe', 'sumber_id');
    }
}
