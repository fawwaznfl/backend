<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Helpers\ApiFormatter;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KunjunganController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $query = Kunjungan::with(['pegawai', 'company'])
            ->orderBy('created_at', 'desc');

        // SUPERADMIN
        if ($user->dashboard_type === 'superadmin') {
            // lihat semua
        }

        // ADMIN
        elseif ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        // PEGAWAI
        elseif ($user->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $user->id);
        }

        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'company_id' => $item->company_id,
                'pegawai' => $item->pegawai,
                'created_at' => $item->created_at,
                'keterangan' => $item->keterangan,
                'lokasi_masuk' => $item->lokasi_masuk,
                'upload_foto' => $item->upload_foto,
                'keterangan_keluar' => $item->keterangan_keluar,
                'updated_at' => $item->updated_at,
                'lokasi_keluar' => $item->lokasi_keluar,
                'foto_keluar' => $item->foto_keluar,
                'tanggal_mulai' => optional($item->created_at)->toDateString(),
                'jam_masuk' => optional($item->created_at)->format('H:i'),
                'jam_pulang' => $item->foto_keluar
                    ? optional($item->updated_at)->format('H:i')
                    : null,
                'status' => $item->foto_keluar ? 'selesai' : 'berlangsung',
            ];
        });

        return ApiFormatter::success($data, 'Data kunjungan berhasil diambil');
}

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'    => 'required|exists:pegawais,id',
            'company_id'    => 'nullable|exists:companies,id',
            'upload_foto'   => 'required|image|max:2048',
            'keterangan'    => 'nullable|string',
            'lokasi_masuk'  => 'required|string',
        ]);

        $user = Auth::user();

        $companyId = $request->company_id
            ?? $user->company_id
            ?? $user->pegawai?->company_id
            ?? null;

        // CEGAH KUNJUNGAN GANDA (BELUM VISIT OUT)
        $cekAktif = Kunjungan::where('pegawai_id', $request->pegawai_id)
            ->whereNull('foto_keluar')
            ->first();

        if ($cekAktif) {
            return ApiFormatter::error('Masih ada kunjungan yang belum selesai', 422);
        }

        $data = Kunjungan::create([
            'company_id'   => $companyId,
            'pegawai_id'   => $request->pegawai_id,
            'upload_foto'  => $request->file('upload_foto')->store('kunjungan_masuk', 'public'),
            'keterangan'   => $request->keterangan,
            'lokasi_masuk' => $request->lokasi_masuk,
            'created_by'   => $user->id,
        ]);

        return ApiFormatter::success($data, 'Visit in berhasil', 201);
    }

    public function show($id)
    {
        $data = Kunjungan::with(['pegawai', 'company'])->find($id);

        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        return ApiFormatter::success($data, 'Detail kunjungan');
    }

    public function update(Request $request, $id)
    {
        $data = Kunjungan::find($id);

        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        // CEGAH DOUBLE VISIT OUT
        if ($data->foto_keluar) {
            return ApiFormatter::error('Kunjungan sudah ditutup', 422);
        }

        $request->validate([
            'foto_keluar'        => 'required|image|max:2048',
            'keterangan_keluar'  => 'nullable|string',
            'lokasi_keluar'      => 'required|string',
        ]);

        // hapus foto lama (jika ada, antisipasi)
        if ($data->foto_keluar && Storage::disk('public')->exists($data->foto_keluar)) {
            Storage::disk('public')->delete($data->foto_keluar);
        }

        $data->update([
            'foto_keluar'       => $request->file('foto_keluar')
                ->store('kunjungan_keluar', 'public'),
            'keterangan_keluar' => $request->keterangan_keluar,
            'lokasi_keluar'     => $request->lokasi_keluar,
            'updated_by'        => Auth::id(),
        ]);

        return ApiFormatter::success($data, 'Visit out berhasil');
    }

    public function destroy($id)
    {
        $data = Kunjungan::find($id);

        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        if ($data->upload_foto && Storage::disk('public')->exists($data->upload_foto)) {
            Storage::disk('public')->delete($data->upload_foto);
        }

        if ($data->foto_keluar && Storage::disk('public')->exists($data->foto_keluar)) {
            Storage::disk('public')->delete($data->foto_keluar);
        }

        $data->delete();

        return ApiFormatter::success(null, 'Kunjungan berhasil dihapus');
    }
}
