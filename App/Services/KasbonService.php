<?php

namespace App\Services;

use App\Models\Kasbon;
use Carbon\Carbon;

class KasbonService
{
    public static function hitungSisa($pegawai)
    {
        $query = Kasbon::where('pegawai_id', $pegawai->id)
            ->where('status', 'approve');

        if ($pegawai->kasbon_periode === 'bulan') {
            $query->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
        }

        if ($pegawai->kasbon_periode === 'tahun') {
            $query->whereYear('tanggal', now()->year);
        }

        $terpakai = $query->sum('nominal');

        return [
            'limit' => $pegawai->saldo_kasbon,
            'terpakai' => $terpakai,
            'sisa' => max($pegawai->saldo_kasbon - $terpakai, 0),
        ];
    }
}
