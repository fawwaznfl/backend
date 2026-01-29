<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Models\Patroli;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatroliRequest;
use App\Http\Requests\UpdatePatroliRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatroliController extends Controller
{
    public function index()
    {
        $data = Patroli::with('company', 'pegawai')->orderBy('created_at', 'desc')->get();
        return ApiFormatter::success($data, 'Data patroli berhasil diambil');
    }

    public function store(StorePatroliRequest $request)
    {
        $user = Auth::user();

        $companyId = $request->company_id
            ?? $user->company_id
            ?? $user->pegawai?->company_id
            ?? null;

        $payload = $request->validated();
        $payload['company_id'] = $companyId;
        $payload['created_by'] = $user->id;

        if ($request->hasFile('bukti_patroli')) {
            $payload['bukti_patroli'] = $request->file('bukti_patroli')->store('bukti_patroli', 'public');
        }

        $data = Patroli::create($payload);

        return ApiFormatter::success($data, 'Data patroli berhasil ditambahkan', 201);
    }

    public function show($id)
    {
        $data = Patroli::with('company', 'pegawai')->find($id);
        if (!$data) {
            return ApiFormatter::error('Data patroli tidak ditemukan', 404);
        }
        return ApiFormatter::success($data, 'Detail patroli berhasil diambil');
    }

    public function update(UpdatePatroliRequest $request, $id)
    {
        $data = Patroli::find($id);
        if (!$data) {
            return ApiFormatter::error('Data patroli tidak ditemukan', 404);
        }

        $updateData = $request->validated();

        if ($request->hasFile('bukti_patroli')) {
            if ($data->bukti_patroli && Storage::disk('public')->exists($data->bukti_patroli)) {
                Storage::disk('public')->delete($data->bukti_patroli);
            }
            $updateData['bukti_patroli'] = $request->file('bukti_patroli')->store('bukti_patroli', 'public');
        }

        $updateData['updated_by'] = Auth::id();

        $data->update($updateData);

        return ApiFormatter::success($data, 'Data patroli berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = Patroli::find($id);
        if (!$data) {
            return ApiFormatter::error('Data patroli tidak ditemukan', 404);
        }

        if ($data->bukti_patroli && Storage::disk('public')->exists($data->bukti_patroli)) {
            Storage::disk('public')->delete($data->bukti_patroli);
        }

        $data->delete();
        return ApiFormatter::success(null, 'Data patroli berhasil dihapus');
    }
}
