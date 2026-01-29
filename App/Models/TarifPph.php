<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifPph extends Model
{
    use HasFactory;

    protected $table = 'tarif_pph';

    protected $fillable = [
        'batas_bawah',
        'batas_atas',
        'tarif',
        'tahun',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
