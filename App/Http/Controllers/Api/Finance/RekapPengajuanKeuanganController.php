<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\RekapPengajuanKeuangan;
use App\Models\Kasbon;
use App\Models\Reimbursement;
use Illuminate\Http\Request;

class RekapPengajuanKeuanganController extends Controller
{
    public function index()
    {
        $data = RekapPengajuanKeuangan::with(['pegawai', 'company'])->latest()->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tipe' => 'required|in:kasbon,reimbursement',
            'sumber_id' => 'required|integer',
        ]);

        // ambil data sumber
        $sumber = match ($validated['tipe']) {
            'kasbon' => Kasbon::findOrFail($validated['sumber_id']),
            'reimbursement' => Reimbursement::findOrFail($validated['sumber_id']),
        };

        $rekap = RekapPengajuanKeuangan::create([
            ...$validated,
            'tanggal_pengajuan' => $sumber->tanggal ?? now(),
            'keterangan' => $sumber->keterangan ?? $sumber->event ?? null,
            'nominal' => $sumber->nominal ?? $sumber->total ?? 0,
        ]);

        return response()->json($rekap->load(['pegawai', 'company']), 201);
    }

    public function show($id)
    {
        $data = RekapPengajuanKeuangan::with(['pegawai', 'company'])->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $rekap = RekapPengajuanKeuangan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'in:menunggu,disetujui,ditolak',
        ]);

        $rekap->update($validated);

        return response()->json($rekap);
    }

    public function destroy($id)
    {
        RekapPengajuanKeuangan::findOrFail($id)->delete();
        return response()->json(['message' => 'Data rekap pengajuan keuangan berhasil dihapus']);
    }
}
