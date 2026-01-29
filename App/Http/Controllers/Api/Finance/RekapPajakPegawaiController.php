<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\RekapPajakPegawai;
use App\Models\TarifPph;
use Illuminate\Http\Request;

class RekapPajakPegawaiController extends Controller
{
    public function index()
    {
        $rekap = RekapPajakPegawai::with(['pegawai', 'company', 'payroll', 'tarifPph'])->get();
        return response()->json($rekap);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'payroll_id' => 'nullable|exists:payrolls,id',
            'tarif_pph_id' => 'nullable|exists:tarif_pph,id',
            'bulan' => 'required|string',
            'tahun' => 'required|digits:4',
            'penghasilan_bruto' => 'required|numeric|min:0',
            'penghasilan_netto' => 'required|numeric|min:0',
            'ptkp' => 'nullable|numeric|min:0',
        ]);

        // Hitung PKP
        $validated['pkp'] = max(0, $validated['penghasilan_netto'] - ($validated['ptkp'] ?? 0));

        // Cari tarif PPh yang sesuai
        $tarif = TarifPph::where('tahun', $validated['tahun'])
            ->where('batas_bawah', '<=', $validated['pkp'])
            ->where(function ($q) use ($validated) {
                $q->where('batas_atas', '>=', $validated['pkp'])
                  ->orWhereNull('batas_atas');
            })
            ->first();

        if ($tarif) {
            $validated['tarif_pph_id'] = $tarif->id;
            $validated['tarif_persen'] = $tarif->tarif;
            $validated['pajak_terutang'] = $validated['pkp'] * ($tarif->tarif / 100);
        } else {
            $validated['tarif_persen'] = 0;
            $validated['pajak_terutang'] = 0;
        }

        $validated['pajak_dipotong'] = $validated['pajak_terutang']; // default
        $validated['pajak_selisih'] = 0;
        $validated['tanggal_proses'] = now();

        $rekap = RekapPajakPegawai::create($validated);

        return response()->json($rekap, 201);
    }

    public function show(RekapPajakPegawai $rekapPajakPegawai)
    {
        return response()->json($rekapPajakPegawai->load(['pegawai', 'company', 'tarifPph']));
    }

    public function update(Request $request, RekapPajakPegawai $rekapPajakPegawai)
    {
        $rekapPajakPegawai->update($request->all());
        return response()->json($rekapPajakPegawai);
    }

    public function destroy(RekapPajakPegawai $rekapPajakPegawai)
    {
        $rekapPajakPegawai->delete();
        return response()->json(['message' => 'Data rekap pajak pegawai berhasil dihapus.']);
    }
}
