<?php

namespace App\Http\Controllers\Api\HR;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreKontrakRequest;
use App\Http\Requests\UpdateKontrakRequest;
use App\Helpers\ApiFormatter as HelpersApiFormatter;
use Illuminate\Http\Request;

class KontrakController extends Controller
{
    /**
     * GET: List Kontrak
     */
    public function index(Request $request)
    {
        $query = Kontrak::with('pegawai')
            ->orderBy('id', 'desc');

        // ✅ filter by pegawai_id (UNTUK /kontrak-kerja/{id})
        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        // ✅ filter by company_id (UNTUK admin)
        if ($request->filled('company_id')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        $kontraks = $query->get()->transform(function ($k) {
            return $this->addFileUrl($k);
        });

        return HelpersApiFormatter::success($kontraks, 'Kontrak fetched');
    }

    /**
     * POST: Create Kontrak
     */
    public function store(StoreKontrakRequest $request)
    {
        $payload = $request->validated();

        // Validasi pegawai
        $pegawai = Pegawai::find($request->pegawai_id);
        if (!$pegawai) {
            return HelpersApiFormatter::error('Pegawai tidak ditemukan', 404);
        }

        if ($pegawai->company_id != $request->company_id) {
            return HelpersApiFormatter::error('Pegawai tidak sesuai dengan company yang dipilih', 422);
        }

        $payload['created_by'] = auth()->id() ?? null;

        // Upload File
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('kontrak_files', 'public');
            $payload['file_kontrak'] = $path;
        }

        $k = Kontrak::create($payload);
        $k = $this->addFileUrl($k);

        return HelpersApiFormatter::success($k, 'Kontrak created', 201);
    }

    /**
     * GET: Detail Kontrak
     */
    public function show($id)
    {
        $k = Kontrak::with('pegawai')->find($id);
        if (!$k) return HelpersApiFormatter::error('Not found', 404);

        $k = $this->addFileUrl($k);
        return HelpersApiFormatter::success($k, 'Kontrak found');
    }

    /**
     * PUT: Update Kontrak
     */
    public function update(UpdateKontrakRequest $request, $id)
    {
        $k = Kontrak::find($id);
        if (!$k) return HelpersApiFormatter::error('Not found', 404);

        $payload = $request->validated();
        $payload['updated_by'] = auth()->id() ?? null;

        // Jika upload file baru → hapus file lama
        if ($request->hasFile('file')) {

            if ($k->file_kontrak && Storage::disk('public')->exists($k->file_kontrak)) {
                Storage::disk('public')->delete($k->file_kontrak);
            }

            $path = $request->file('file')->store('kontrak_files', 'public');
            $payload['file_kontrak'] = $path;
        }

        $k->update($payload);
        $k = $this->addFileUrl($k);

        return HelpersApiFormatter::success($k, 'Kontrak updated');
    }

    /**
     * DELETE: Delete Kontrak
     */
    public function destroy($id)
    {
        $k = Kontrak::find($id);
        if (!$k) return HelpersApiFormatter::error('Not found', 404);

        // Hapus file di storage
        if ($k->file_kontrak && Storage::disk('public')->exists($k->file_kontrak)) {
            Storage::disk('public')->delete($k->file_kontrak);
        }

        $k->delete();

        return HelpersApiFormatter::success(null, 'Kontrak deleted', 204);
    }

    /**
     * Tambahkan URL lengkap untuk file kontrak
     */
    private function addFileUrl($kontrak)
    {
        if ($kontrak->file_kontrak) {
            $kontrak->file_kontrak = asset('storage/' . $kontrak->file_kontrak);
        }
        return $kontrak;
    }
}
