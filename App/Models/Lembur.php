<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'pegawai_id',

        'tanggal_lembur',
        'jam_mulai',
        'jam_selesai',
        'total_lembur_menit',

        'foto_masuk',
        'foto_pulang',

        'lokasi_masuk',
        'lokasi_pulang',

        'keterangan',
        'status',
        'approved_by',

        'created_by',
        'updated_by',
    ];

    /**
     * Append otomatis ke JSON response
     */
    protected $appends = [
        'foto_masuk_url',
        'foto_pulang_url',
    ];

    // =====================
    // ACCESSOR FOTO
    // =====================

    public function getFotoMasukUrlAttribute()
    {
        return $this->foto_masuk
            ? asset('storage/' . $this->foto_masuk)
            : null;
    }

    public function getFotoPulangUrlAttribute()
    {
        return $this->foto_pulang
            ? asset('storage/' . $this->foto_pulang)
            : null;
    }

    // =====================
    // RELASI
    // =====================

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'disetujui_oleh');
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
        static::created(function ($lembur) {
            $pegawai = $lembur->pegawai;
            
            NotificationService::create(
                [
                    'type' => 'lembur_submitted',
                    'title' => 'Pengajuan Lembur',
                    'message' => "Pegawai {$pegawai->name} mengajukan lembur",
                    'company_id' => $pegawai->company_id,
                    'data' => [
                        'lembur_id' => $lembur->id,
                        'pegawai_id' => $pegawai->id,
                        'pegawai_nama' => $pegawai->name,
                    ]
                ],
                [
                    [
                        'role' => 'admin',
                        'company_id' => $pegawai->company_id,
                    ],
                    [
                        'role' => 'superadmin',
                    ],
                ]
            );
        });
    }

}
