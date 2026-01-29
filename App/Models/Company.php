<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alamat',
        'telepon',
        'email',
        'website',
    ];

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function kontraks()
    {
        return $this->hasMany(Kontrak::class);
    }

}
