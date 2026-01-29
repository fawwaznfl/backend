<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKinerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'nama',
        'detail',
        'bobot_penilaian',
        'created_by',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
