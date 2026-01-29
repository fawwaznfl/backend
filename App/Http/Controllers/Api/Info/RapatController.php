<?php

namespace App\Http\Controllers\Api\Info;

use App\Http\Controllers\Controller;
use App\Models\Rapat;
use App\Http\Requests\StoreRapatRequest;
use App\Http\Requests\UpdateRapatRequest;
use App\Helpers\ApiFormatter;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RapatController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // ==========================
        // DASHBOARD PEGAWAI
        // ==========================
        if (
            $user instanceof Pegawai &&
            $user->dashboard_type === 'pegawai'
        ) {
            $data = Rapat::whereHas('pegawais', function ($q) use ($user) {
                $q->where('pegawai_id', $user->id);
            })
            ->with('pegawais:id,name')
            ->orderBy('tanggal_rapat', 'desc')
            ->get();

            return ApiFormatter::success(
                $data,
                'Daftar rapat pegawai'
            );
        }

        // ==========================
        // ADMIN & SUPERADMIN
        // ==========================
        // ✔ otomatis terfilter company oleh CompanyScope
        $data = Rapat::with('pegawais:id,name')
            ->orderBy('tanggal_rapat', 'desc')
            ->get();

        return ApiFormatter::success(
            $data,
            'Daftar rapat'
        );
    }


    public function store(StoreRapatRequest $request)
    {
        $payload = $request->validated();

        // ✅ Fallback aman untuk company_id
        $payload['company_id'] = $request->company_id
            ?? Auth::user()?->company_id
            ?? Auth::user()?->pegawai?->company_id
            ?? null;

        // ✅ Upload file notulen jika ada
        if ($request->hasFile('file_notulen')) {
            $payload['file_notulen'] = $request->file('file_notulen')->store('notulen', 'public');
        }

        $payload['created_by'] = Auth::id();

        $rapat = Rapat::create($payload);

        if ($request->pegawai_ids) {
            $rapat->pegawais()->sync($request->pegawai_ids);
        }

        return ApiFormatter::success($rapat, 'Rapat berhasil dibuat', 201);
    }

    public function show($id)
    {
        $user = Auth::user();

        $pegawai = $user instanceof \App\Models\Pegawai
            ? $user
            : $user->pegawai;


        $rapat = Rapat::with([
            'pegawais' => function ($q) {
                $q->select('pegawais.id', 'name', 'foto_karyawan')
                ->withPivot('waktu_hadir');
            }
        ])->find($id);

        if (!$rapat) {
            return ApiFormatter::error('Data rapat tidak ditemukan', 404);
        }

        return ApiFormatter::success([
            ...$rapat->toArray(),
            'auth_pegawai_id' => $pegawai?->id,
        ], 'Detail rapat ditemukan');
    }

    public function update(UpdateRapatRequest $request, $id)
    {
        $rapat = Rapat::findOrFail($id);
        $payload = $request->validated();

        // ✅ Upload file notulen baru jika dikirim
        if ($request->hasFile('file_notulen')) {
            if ($rapat->file_notulen && Storage::disk('public')->exists($rapat->file_notulen)) {
                Storage::disk('public')->delete($rapat->file_notulen);
            }
            $payload['file_notulen'] = $request->file('file_notulen')->store('notulen', 'public');
        }

        $payload['updated_by'] = Auth::id();
        $rapat->update($payload);

        return ApiFormatter::success($rapat, 'Rapat berhasil diperbarui');
    }

    public function destroy($id)
    {
        $rapat = Rapat::findOrFail($id);

        // ✅ Hapus file notulen jika ada
        if ($rapat->file_notulen && Storage::disk('public')->exists($rapat->file_notulen)) {
            Storage::disk('public')->delete($rapat->file_notulen);
        }

        $rapat->delete();

        return ApiFormatter::success(null, 'Rapat berhasil dihapus', 200);
    }

    public function hadir($id)
    {
        $pegawai = Auth::user();

        // pastikan yang login adalah pegawai
        if (!$pegawai instanceof \App\Models\Pegawai) {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $rapat = Rapat::find($id);
        if (!$rapat) {
            return ApiFormatter::error('Rapat tidak ditemukan', 404);
        }

        // cek apakah pegawai terdaftar di rapat
        $pivot = $rapat->pegawais()
            ->where('pegawai_id', $pegawai->id)
            ->first();

        if (!$pivot) {
            return ApiFormatter::error('Pegawai tidak terdaftar di rapat ini', 403);
        }

        // cegah hadir dua kali
        if ($pivot->pivot->waktu_hadir) {
            return ApiFormatter::success(null, 'Anda sudah hadir');
        }

        // update pivot
        $rapat->pegawais()->updateExistingPivot($pegawai->id, [
            'waktu_hadir' => now(),
        ]);

        return ApiFormatter::success(null, 'Berhasil mencatat kehadiran');
    }

    public function simpanNotulen(Request $request, $id)
    {
        $request->validate([
            'notulen' => 'required|string',
        ]);

        $rapat = Rapat::find($id);
        if (!$rapat) {
            return ApiFormatter::error('Rapat tidak ditemukan', 404);
        }

        $rapat->update([
            'notulen' => $request->notulen,
            'updated_by' => Auth::id(),
        ]);

        return ApiFormatter::success(
            $rapat,
            'Notulen berhasil disimpan'
        );
    }

}
