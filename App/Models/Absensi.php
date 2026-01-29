<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'pegawai_id',
        'shift_id',
        'lokasi_id',

        'tanggal',
        'jam_masuk',
        'jam_pulang',

        'foto_masuk',
        'foto_pulang',

        'lokasi_masuk',
        'lokasi_pulang',

        'telat',
        'pulang_cepat',

        'keterangan',

        'latitude',
        'longitude',

        'status',
        'verifikasi',
        'approved_by',

        'created_by',
        'updated_by',
    ];

    // === Relasi
    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class);
    }

    public function shift() {
        return $this->belongsTo(Shift::class);
    }

    public function lokasi() {
        return $this->belongsTo(Lokasi::class);
    }

    public function approver() {
        return $this->belongsTo(Pegawai::class, 'approved_by');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
