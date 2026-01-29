<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Services\RekapAbsensiService;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    protected RekapAbsensiService $rekapService;

    public function __construct(RekapAbsensiService $rekapService)
    {
        $this->rekapService = $rekapService;
    }

    public function rekapPegawai(Request $request, $pegawaiId)
    {
        $data = $this->rekapService->rekapPegawai($request, $pegawaiId);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
