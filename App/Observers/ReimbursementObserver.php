<?php

namespace App\Observers;

use App\Models\Reimbursement;
use App\Models\RekapPengajuanKeuangan;

class ReimbursementObserver
{
    public function created(Reimbursement $reimbursement)
    {
        RekapPengajuanKeuangan::create([
            'company_id' => $reimbursement->company_id,
            'pegawai_id' => $reimbursement->pegawai_id,
            'tipe' => 'reimbursement',
            'sumber_id' => $reimbursement->id,
            'tanggal_pengajuan' => $reimbursement->tanggal,
            'keterangan' => $reimbursement->event ?? 'Reimbursement',
            'nominal' => $reimbursement->jumlah ?? 0,
            'status' => 'menunggu',
        ]);
    }

    public function deleted(Reimbursement $reimbursement)
    {
        RekapPengajuanKeuangan::where('tipe', 'reimbursement')
            ->where('sumber_id', $reimbursement->id)
            ->delete();
    }
}
