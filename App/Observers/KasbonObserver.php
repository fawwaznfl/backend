<?php

namespace App\Observers;

use App\Models\Kasbon;
use App\Models\RekapPengajuanKeuangan;

class KasbonObserver
{
    public function created(Kasbon $kasbon)
    {
        RekapPengajuanKeuangan::create([
            'company_id' => $kasbon->company_id,
            'pegawai_id' => $kasbon->pegawai_id,
            'tipe' => 'kasbon',
            'sumber_id' => $kasbon->id,
            'tanggal_pengajuan' => $kasbon->tanggal,
            'keterangan' => $kasbon->keperluan ?? 'Kasbon Pegawai',
            'nominal' => $kasbon->nominal,
            'status' => 'menunggu',
        ]);
    }

    public function deleted(Kasbon $kasbon)
    {
        RekapPengajuanKeuangan::where('tipe', 'kasbon')
            ->where('sumber_id', $kasbon->id)
            ->delete();
    }
}
