<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinasLuarMapping extends Model
{
    //protected $table = 'shift_mapping';
    protected $table = 'dinas_luar_mapping';

    protected $fillable = [
        'pegawai_id',
        'shift_id',
        'company_id',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
