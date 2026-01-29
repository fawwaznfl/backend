<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;

class ShiftMapping extends Model
{
    protected $table = 'shift_mapping';

    protected $fillable = [
        'pegawai_id',
        'shift_id',
        'shift_lama_id',
        'company_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'toleransi_telat',
        'status_toleransi',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function shiftLama()
    {
        return $this->belongsTo(Shift::class, 'shift_lama_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'shift_id', 'shift_id');
    }

}
