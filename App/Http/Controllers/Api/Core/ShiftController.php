<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShiftRequest;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    // === GET Semua Data ===
    public function index()
    {
        $shifts = Shift::with(['company'])->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Shift',
            'data' => $shifts,
        ]);
    }

    // === GET Detail ===
    public function show($id)
    {
        $shift = Shift::with(['company'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Detail Shift',
            'data' => $shift,
        ]);
    }

    // === CREATE ===
    public function store(StoreShiftRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        $shift = Shift::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil dibuat',
            'data' => $shift,
        ], 201);
    }

    // === UPDATE ===
    public function update(StoreShiftRequest $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $shift->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil diperbarui',
            'data' => $shift,
        ]);
    }

    // === DELETE ===
    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil dihapus',
        ]);
    }
}
