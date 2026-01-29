<?php

namespace App\Http\Controllers\Api\Info;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Http\Requests\StoreNotifikasiRequest;
use App\Http\Requests\UpdateNotifikasiRequest;
use App\Helpers\ApiFormatter;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Jika superadmin atau user belum punya pegawai 
        if ($user->role->nama === 'superadmin' || !$user->pegawai) {
            $data = Notifikasi::orderBy('id', 'desc')->get();
        } else {
            $companyId = $user->pegawai->company_id ?? null;

            $data = Notifikasi::where(function ($q) use ($user, $companyId) {
                        $q->where('company_id', $companyId)
                        ->orWhere('user_id', $user->id)
                        ->orWhere('is_broadcast', true);
                    })
                    ->orderBy('id', 'desc')
                    ->get();
        }

        return ApiFormatter::success($data, 'Notifikasi fetched');
    }




    public function store(StoreNotifikasiRequest $request)
    {
        $user = auth()->user();

        if ($user->role->nama !== 'superadmin') {
            return ApiFormatter::error('Hanya superadmin yang boleh menambah notifikasi', 403);
        }

        $payload = $request->validated();
        $payload['created_by'] = $user->id;
        $payload['tanggal_kirim'] = now();

        $notifikasi = Notifikasi::create($payload);
        return ApiFormatter::success($notifikasi, 'Notifikasi created', 201);
    }

    public function show($id)
    {
        $notifikasi = Notifikasi::find($id);
        if (!$notifikasi) return ApiFormatter::error('Notifikasi not found', 404);
        return ApiFormatter::success($notifikasi, 'Notifikasi found');
    }

    public function update(UpdateNotifikasiRequest $request, $id)
    {
        $user = auth()->user();
        if ($user->role->nama !== 'superadmin') {
            return ApiFormatter::error('Hanya superadmin yang boleh mengubah notifikasi', 403);
        }

        $notifikasi = Notifikasi::find($id);
        if (!$notifikasi) return ApiFormatter::error('Notifikasi not found', 404);

        $payload = $request->validated();
        $payload['updated_by'] = $user->id;

        $notifikasi->update($payload);
        return ApiFormatter::success($notifikasi, 'Notifikasi updated');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->role->nama !== 'superadmin') {
            return ApiFormatter::error('Hanya superadmin yang boleh menghapus notifikasi', 403);
        }

        $notifikasi = Notifikasi::find($id);
        if (!$notifikasi) return ApiFormatter::error('Notifikasi not found', 404);

        $notifikasi->delete();
        return ApiFormatter::success(null, 'Notifikasi deleted', 204);
    }
}
