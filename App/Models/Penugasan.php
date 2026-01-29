<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;

    protected $fillable = [
    'company_id',
    'pegawai_id',
    'judul_pekerjaan',
    'rincian_pekerjaan',
    'status',
    'nomor_penugasan',
    'created_by',
];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);

        static::creating(function ($penugasan) {
            if (!$penugasan->nomor_penugasan) {
                $random = mt_rand(1000, 9999);
                $penugasan->nomor_penugasan = 'T-' . $random;
            }
        });
    }
}
