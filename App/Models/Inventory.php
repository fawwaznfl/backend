<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'lokasi_id',
        'divisi_id',
        'kode_barang',
        'nama_barang',
        'stok',
        'satuan',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    

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

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
}
