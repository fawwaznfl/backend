<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $fillable = [
        'company_id',
        'pegawai_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'foto',
        'status',
        'disetujui_oleh',
        'created_by',
        'updated_by',
        'catatan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Pegawai::class, 'disetujui_oleh');
    }

    protected static function booted()
    {
        static::created(function ($cuti) {
            $pegawai = $cuti->pegawai;
            
            NotificationService::create(
                [
                    'type' => 'cuti_submitted',
                    'title' => 'Pengajuan Cuti',
                    'message' => "Pegawai {$pegawai->name} mengajukan cuti",
                    'company_id' => $pegawai->company_id,
                    'data' => [
                        'cuti_id' => $cuti->id,
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
