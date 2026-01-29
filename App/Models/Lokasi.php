<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'nama_lokasi',
       // 'alamat',
        'lat_kantor',
        'long_kantor',
        'radius',
        'keterangan',
        'status',
        'created_by',
        'updated_by'
    ];
}
