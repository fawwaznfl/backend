<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model {
    use HasFactory;
    
    protected $fillable = [
    'pegawai_id',
    'company_id',
    'tanggal_mulai',
    'tanggal_selesai',
    'jenis_kontrak',
    'file_kontrak',
    'keterangan',
    'notified_h30',
    'notified_h7',
    'created_by',
    'updated_by'
];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_kontrak ? asset('storage/' . $this->file_kontrak) : null;
    }
}
