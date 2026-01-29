<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetKinerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'nomor_target',
        'pegawai_id',
        'divisi_id',
        'target_pribadi',
        'jumlah_persen_pribadi',
        'bonus_pribadi',
        'target_team',
        'jumlah_persen_team',
        'bonus_team',
        'tanggal_mulai',
        'tanggal_selesai',
        'jackpot',
    ];

    protected static function boot()
    {
        parent::boot();

        // Otomatis buat nomor target TKxxxx
        static::creating(function ($model) {
            $last = self::orderBy('id', 'desc')->first();
            $nextNumber = $last ? $last->id + 1 : 1;
            $model->nomor_target = 'TK' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Hitung bonus pribadi & team otomatis
            $model->bonus_pribadi = ($model->target_pribadi * $model->jumlah_persen_pribadi) / 100;
            $model->bonus_team = ($model->target_team * $model->jumlah_persen_team) / 100;
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
