<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','nama','deskripsi','created_by','updated_by'];

    public function pegawais() { return $this->hasMany(Pegawai::class, 'divisi_id'); }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}