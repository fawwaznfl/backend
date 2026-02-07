<?php

namespace App\Helpers;

use Carbon\Carbon;

class KasbonHelper
{
    public static function getPeriode(string $satuan): array
    {
        if ($satuan === 'bulan') {
            return [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ];
        }

        // default: tahun
        return [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()
        ];
    }
}
