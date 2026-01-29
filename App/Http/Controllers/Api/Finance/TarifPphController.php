<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\TarifPph;
use Illuminate\Http\Request;

class TarifPphController extends Controller
{
    public function index()
    {
        return response()->json(TarifPph::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'batas_bawah' => 'required|numeric|min:0',
            'batas_atas' => 'nullable|numeric|min:0',
            'tarif' => 'required|numeric|min:0',
            'tahun' => 'required|digits:4',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $tarif = TarifPph::create($validated);

        return response()->json($tarif, 201);
    }

    public function show(TarifPph $tarifPph)
    {
        return response()->json($tarifPph);
    }

    public function update(Request $request, TarifPph $tarifPph)
    {
        $validated = $request->validate([
            'batas_bawah' => 'nullable|numeric|min:0',
            'batas_atas' => 'nullable|numeric|min:0',
            'tarif' => 'nullable|numeric|min:0',
            'tahun' => 'nullable|digits:4',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $tarifPph->update($validated);

        return response()->json($tarifPph);
    }

    public function destroy(TarifPph $tarifPph)
    {
        $tarifPph->delete();
        return response()->json(['message' => 'Data tarif PPh berhasil dihapus.']);
    }
}
