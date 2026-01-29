<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patroli extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'pegawai_id',
        'tanggal',
        'lokasi',
        'tujuan',
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

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
