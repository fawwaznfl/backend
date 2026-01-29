<?php

namespace App\Http\Controllers\Api\RekapData;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\LaporanKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LaporanKerjaController extends Controller
{

    public function index(Request $request)
    {
        $query = LaporanKerja::with('pegawai')->latest();

        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        return ApiFormatter::success(
            $query->get(),
            'Daftar Laporan Kerja'
        );
    }


    // ================= GET BY PEGAWAI =================
    public function byPegawai($pegawai_id)
    {
        $data = LaporanKerja::where('pegawai_id', $pegawai_id)
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    // ================= STORE =================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'informasi_umum' => 'required|string',
            'pekerjaan_dilaksanakan' => 'required|string',
            'pekerjaan_belum_dilaksanakan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $data = LaporanKerja::create([
            'pegawai_id' => $validated['pegawai_id'],
            'informasi_umum' => $validated['informasi_umum'],
            'pekerjaan_yang_dilaksanakan' => $validated['pekerjaan_dilaksanakan'],
            'pekerjaan_belum_selesai' => $validated['pekerjaan_belum_dilaksanakan'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'tanggal_laporan' => now()->toDateString(),
        ]);

        return ApiFormatter::success($data, 'Laporan kerja berhasil disimpan');
    }


    // ================= SHOW =================
    public function show($id)
    {
        return ApiFormatter::success(
            LaporanKerja::findOrFail($id),
            'Detail Laporan Kerja'
        );
    }


    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $laporan = LaporanKerja::findOrFail($id);

        $laporan->update([
            'informasi_umum' => $request->informasi_umum,
            'pekerjaan_yang_dilaksanakan' => $request->pekerjaan_yang_dilaksanakan,
            'pekerjaan_belum_selesai' => $request->pekerjaan_belum_selesai,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diupdate'
        ]);
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        LaporanKerja::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ]);
    }

    public function myLaporan()
    {
        $pegawaiId = Auth::guard('pegawai')->id();

        return ApiFormatter::success(
            LaporanKerja::where('pegawai_id', $pegawaiId)->latest()->get(),
            'Laporan Kerja Saya'
        );
    }

}
