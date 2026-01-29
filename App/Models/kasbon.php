<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;

class kasbon extends Model
{
    protected $fillable = [
    'company_id',
    'pegawai_id',
    'tanggal',
    'nominal',
    'keperluan',
    'status',
    'metode_pengiriman',
    'nomor_rekening',
    'file_approve',
    'disetujui_oleh',
    'created_by',
    'updated_by',
    ];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::created(function ($kasbon) {
            $pegawai = $kasbon->pegawai;
            
            NotificationService::create(
                [
                    'type' => 'kasbon_submitted',
                    'title' => 'Pengajuan Kasbon',
                    'message' => "Pegawai {$pegawai->name} mengajukan kasbon",
                    'company_id' => $pegawai->company_id,
                    'data' => [
                        'kasbon_id' => $kasbon->id,
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
