<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    protected $fillable = [
        'company_id',
        'pegawai_id',
        'kategori_reimbursement_id',
        'tanggal',
        'event',
        'jumlah',
        'terpakai',
        'total',
        'sisa',
        'status',
        'approved_file',
        'file',
        'metode_reim',     
        'no_rekening',
    ];

    // 🟩 Auto tampilkan field "total" & "sisa" di response
    protected $appends = ['total', 'sisa'];

    public function kategori()
    {
        return $this->belongsTo(KategoriReimbursement::class, 'kategori_reimbursement_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // 🟩 TOTAL otomatis
    public function getTotalAttribute()
    {
        // Kalau kolom total ada → pakai kolom tersebut
        if (!is_null($this->attributes['total'] ?? null)) {
            return $this->attributes['total'];
        }

        // Kalau tidak ada → fallback ke jumlah / kategori
        return $this->jumlah ?? 0;
    }

    // 🟩 SISA otomatis
    public function getSisaAttribute()
    {
        $total = $this->total;
        $terpakai = $this->terpakai ?? 0;

        return $total - $terpakai;
    }

    protected static function booted()
    {
        static::created(function ($reimbursement) {
            $pegawai = $reimbursement->pegawai;
            
            NotificationService::create(
                [
                    'type' => 'reimbursement_submitted',
                    'title' => 'Pengajuan Reimbursement',
                    'message' => "Pegawai {$pegawai->name} mengajukan reimbursement",
                    'company_id' => $pegawai->company_id,
                    'data' => [
                        'reimbursement_id' => $reimbursement->id,
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
