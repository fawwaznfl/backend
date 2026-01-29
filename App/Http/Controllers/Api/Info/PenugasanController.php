<?php

namespace App\Http\Controllers\Api\Info;

use App\Http\Controllers\Controller;
use App\Helpers\ApiFormatter;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenugasanController extends Controller
{
    public function index()
{
    $pegawai = Auth::user(); // INI PEGAWAI, BUKAN USER

    $query = Penugasan::with(['pegawai', 'company'])
        ->orderByDesc('id');

    // ===============================
    // SUPERADMIN → semua data
    // ===============================
    if ($pegawai->dashboard_type === 'superadmin') {
        // tidak difilter
    }

    // ===============================
    // ADMIN → berdasarkan company
    // ===============================
    elseif ($pegawai->dashboard_type === 'admin') {
        $query->where('company_id', $pegawai->company_id);
    }

    // ===============================
    // PEGAWAI → hanya data dirinya
    // ===============================
    elseif ($pegawai->dashboard_type === 'pegawai') {
        $query->where('pegawai_id', $pegawai->id);
    }

    return ApiFormatter::success(
        $query->get(),
        'Data penugasan berhasil diambil'
    );
}

    // Menampilkan detail penugasan
    public function show($id)
    {
        $data = Penugasan::with(['pegawai', 'company'])->find($id);

        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        return ApiFormatter::success($data, 'Detail penugasan berhasil diambil');
    }

    // Menambahkan penugasan baru
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'company_id' => 'nullable|exists:companies,id',
            'judul_pekerjaan' => 'required|string|max:255',
            'rincian_pekerjaan' => 'nullable|string',
            'status' => 'nullable|in:process,pending,finish',
        ]);

        $user = Auth::user();

        $companyId = $request->company_id
            ?? $user->company_id
            ?? $user->pegawai?->company_id
            ?? null;

        $data = Penugasan::create([
            'pegawai_id' => $request->pegawai_id,
            'company_id' => $companyId,
            'judul_pekerjaan' => $request->judul_pekerjaan,
            'rincian_pekerjaan' => $request->rincian_pekerjaan,
            'status' => $request->status ?? 'pending',
            'created_by' => $user->id,
        ]);

        return ApiFormatter::success($data, 'Penugasan berhasil dibuat', 201);
    }

    // Mengupdate penugasan
    public function update(Request $request, $id)
    {
        $data = Penugasan::find($id);
        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        $request->validate([
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'company_id' => 'nullable|exists:companies,id',
            'judul_pekerjaan' => 'nullable|string|max:255',
            'rincian_pekerjaan' => 'nullable|string',
            'status' => 'nullable|in:process,pending,finish',
        ]);

        $data->update($request->only([
            'pegawai_id',
            'company_id',
            'judul_pekerjaan',
            'rincian_pekerjaan',
            'status'
        ]));

        return ApiFormatter::success($data, 'Penugasan berhasil diperbarui');
    }

    // Menghapus penugasan
    public function destroy($id)
    {
        $data = Penugasan::find($id);
        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        $data->delete();

        return ApiFormatter::success(null, 'Penugasan berhasil dihapus');
    }
}
