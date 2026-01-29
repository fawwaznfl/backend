<?php

namespace App\Models;

use App\Scope\CompanyScope;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapat extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'nama_pertemuan',
        'detail_pertemuan',
        'tanggal_rapat',
        'lokasi',
        'waktu_mulai',
        'waktu_selesai',
        'notulen',
        'jenis_pertemuan',
        'file_notulen',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
    'tanggal_rapat' => 'date',
    'waktu_mulai' => 'string',
    'waktu_selesai' => 'string',
    ];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

     public function pegawais()
    {
        return $this->belongsToMany(
            Pegawai::class,
            'rapat_pegawai',
            'rapat_id',
            'pegawai_id',
        )->withPivot(['waktu_hadir'])
        ->withTimestamps();
    }

    protected static function booted()
    {
        static::created(function ($meeting) {
            // Ambil semua pegawai yang terkait dengan meeting ini
            $pegawaiIds = $meeting->pegawai()->pluck('pegawai_id')->toArray();
            
            // Buat notifikasi untuk setiap pegawai
            if (!empty($pegawaiIds)) {
                foreach ($pegawaiIds as $pegawaiId) {
                    NotificationService::create(
                        [
                            'type' => 'meeting_created',
                            'title' => 'Undangan Rapat',
                            'message' => "Anda diundang ke rapat: {$meeting->title} pada {$meeting->tanggal}",
                            'company_id' => $meeting->company_id,
                            'data' => [
                                'meeting_id' => $meeting->id,
                                'meeting_title' => $meeting->title,
                                'tanggal' => $meeting->tanggal,
                                'jam_mulai' => $meeting->jam_mulai,
                            ]
                        ],
                        [
                            [
                                'user_id' => $pegawaiId,
                            ],
                        ]
                    );
                }
            }
        });
    }
}
