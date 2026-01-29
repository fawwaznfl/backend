<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'judul',
        'isi',
        'tipe',
        'is_broadcast',
        'tanggal_kirim',
        'created_by',
        'updated_by',
    ];

    public function company() { return $this->belongsTo(Company::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
