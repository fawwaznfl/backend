<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKerja extends Model
{
    protected $table = 'laporan_kerja';

    protected $fillable = [
        'pegawai_id',
        'informasi_umum',
        'pekerjaan_yang_dilaksanakan',
        'pekerjaan_belum_selesai',
        'catatan',
        'tanggal_laporan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
