<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'kode_shift',
        'nama',
        'jenis_shift',
        'jam_masuk',
        'jam_pulang',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_jam',
        'is_libur',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    // === RELASI ===
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
