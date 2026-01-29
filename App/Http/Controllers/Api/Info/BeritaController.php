<?php

namespace App\Http\Controllers\Api\Info;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Http\Requests\StoreBeritaRequest;
use App\Http\Requests\UpdateBeritaRequest;
use App\Helpers\ApiFormatter;

class BeritaController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Berita::orderBy('tanggal_publikasi', 'desc');

        // Jika ada param company_id → filter
        if ($request->has('company_id') && $request->company_id !== 'all') {
            $query->where('company_id', $request->company_id);
        }

        return ApiFormatter::success(
            $query->get(),
            'Data berita berhasil diambil'
        );
    }


    public function show($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return ApiFormatter::error(null, 'Data tidak ditemukan', 404);
        }

        return ApiFormatter::success($berita, 'Detail berita');
    }

    public function store(StoreBeritaRequest $request)
    {
        $data = $request->validated();

        // Set tanggal publikasi otomatis
        $data['tanggal_publikasi'] = now();

        // upload gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create($data);

        return ApiFormatter::success($berita, 'Berita berhasil dibuat');
    }


    public function update(UpdateBeritaRequest $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return ApiFormatter::error(null, 'Data tidak ditemukan', 404);
        }

        $data = $request->validated();

        // upload gambar baru
        if ($request->hasFile('gambar')) {
            if ($berita->gambar && file_exists(storage_path('app/public/' . $berita->gambar))) {
                unlink(storage_path('app/public/' . $berita->gambar));
            }

            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita->update($data);

        return ApiFormatter::success($berita, 'Berita berhasil diperbarui');
    }

    public function destroy($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return ApiFormatter::error(null, 'Data tidak ditemukan', 404);
        }

        if ($berita->gambar && file_exists(storage_path('app/public/' . $berita->gambar))) {
            unlink(storage_path('app/public/' . $berita->gambar));
        }

        $berita->delete();

        return ApiFormatter::success(null, 'Berita berhasil dihapus');
    }
}
