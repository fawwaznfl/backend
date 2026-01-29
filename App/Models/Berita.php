<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'tipe',
        'judul',
        'isi_konten',
        'gambar',
        'tanggal_publikasi',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
