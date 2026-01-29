<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriReimbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'nama',
        'jumlah',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
