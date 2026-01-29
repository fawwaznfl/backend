<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans';

    protected $fillable = [
        'company_id',
        'pegawai_id',
        'upload_foto',
        'keterangan',
        'lokasi_masuk',
        'foto_keluar',
        'keterangan_keluar',
        'lokasi_keluar',
        'created_by',
        'updated_by',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
