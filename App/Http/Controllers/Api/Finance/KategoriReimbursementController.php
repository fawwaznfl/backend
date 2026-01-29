<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\KategoriReimbursement;
use Illuminate\Http\Request;

class KategoriReimbursementController extends Controller
{
    // Ambil company_id user (pegawai/admin)
    private function getCompanyId()
    {
        $user = auth()->user();
        return $user->pegawai->company_id ?? $user->company_id;
    }

    /**
     * GET /kategori-reimbursement
     * Pegawai/Admin → hanya lihat kategori company sendiri
     * Superadmin → bisa lihat semua (opsional)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $companyId = $this->getCompanyId();

        // Jika superadmin → boleh lihat semua
        if ($user->role === 'superadmin') {
            return response()->json([
                "data" => KategoriReimbursement::all()
            ]);
        }

        // Selain superadmin → hanya lihat kategori company sendiri
        $data = KategoriReimbursement::where('company_id', $companyId)->get();

        return response()->json([
            "data" => $data
        ]);
    }

    /**
     * POST create
     * company_id tidak boleh dari frontend → auto berdasarkan user
     */
    public function store(Request $request)
    {
        $companyId = $this->getCompanyId();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'nullable|numeric',
        ]);

        $validated['company_id'] = $companyId; // lock company

        $kategori = KategoriReimbursement::create($validated);

        return response()->json($kategori, 201);
    }

    /**
     * GET detail kategori
     * Hanya bisa lihat kategori company sendiri
     */
    public function show($id)
    {
        $companyId = $this->getCompanyId();

        $kategori = KategoriReimbursement::where('company_id', $companyId)
            ->where('id', $id)
            ->first();

        if (!$kategori) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }

        return response()->json($kategori);
    }

    /**
     * PUT update
     * Hanya bisa update kategori company sendiri
     */
    public function update(Request $request, $id)
    {
        $companyId = $this->getCompanyId();

        $kategori = KategoriReimbursement::where('company_id', $companyId)
            ->where('id', $id)
            ->first();

        if (!$kategori) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'jumlah' => 'nullable|numeric',
        ]);

        $kategori->update($validated);

        return response()->json($kategori);
    }

    /**
     * DELETE kategori
     * Hanya bisa hapus kategori company sendiri
     */
    public function destroy($id)
    {
        $companyId = $this->getCompanyId();

        $kategori = KategoriReimbursement::where('company_id', $companyId)
            ->where('id', $id)
            ->first();

        if (!$kategori) {
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }

        $kategori->delete();

        return response()->json(['message' => 'Kategori reimbursement berhasil dihapus']);
    }
}
