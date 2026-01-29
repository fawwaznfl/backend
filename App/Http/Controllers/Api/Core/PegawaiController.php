<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Rapat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * LIST PEGAWAI
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // SUPERADMIN → bebas semua pegawai TANPA GlobalScope
        if ($user->dashboard_type === 'superadmin') {
            $pegawai = Pegawai::withoutGlobalScopes()
                ->with(['company', 'divisi', 'lokasi', 'role'])
                ->get();
        }

        // ADMIN → pegawai hanya dari company nya
        elseif ($user->dashboard_type === 'admin') {
            $pegawai = Pegawai::where('company_id', $user->company_id)
                ->with(['company', 'divisi', 'lokasi', 'role'])
                ->get();
        }

        // PEGAWAI → hanya dirinya sendiri
        else {
            $pegawai = Pegawai::where('id', $user->id)
                ->with(['company', 'divisi', 'lokasi', 'role'])
                ->get();
        }

        // Tambah foto URL
        $pegawai->map(function ($item) {
            $item->foto_karyawan = $item->foto_karyawan_url;
            return $item;
        });

        return response()->json($pegawai);
    }

    /**
     * DETAIL PEGAWAI
     */
    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->foto_karyawan = $pegawai->foto_karyawan_url;

        return response()->json($pegawai);
    }

    /**
     * UPDATE PEGAWAI
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // ========== VALIDASI ==========
        $validated = $request->validate([
            'name' => 'nullable|string|max:255', 
            'email' => 'nullable|email',
            'username' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',

            'company_id' => 'nullable|integer',
            'divisi_id' => 'nullable|integer',
            'lokasi_id' => 'nullable|integer',
            'role_id' => 'nullable|integer',

            'dashboard_type' => 'nullable|string',
            'status' => 'nullable|string',

            'tgl_lahir' => 'nullable|date',
            'tgl_join' => 'nullable|date',
            'masa_berlaku' => 'nullable|date',

            'gender' => 'nullable|in:Laki-laki,Perempuan',
        
            'status_nikah' => 'nullable|in:TK/0,TK/1,TK/2,TK/3,K0,K1,K2,K3',
            'status_pajak' => 'nullable|in:TK/0,TK/1,TK/2,TK/3,K0,K1,K2,K3',

            'ktp' => 'nullable|string|max:50',
            'kartu_keluarga' => 'nullable|string|max:50',
            'bpjs_kesehatan' => 'nullable|string|max:50',
            'bpjs_ketenagakerjaan' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'sim' => 'nullable|string|max:50',

            'no_pkwt' => 'nullable|string',
            'no_kontrak' => 'nullable|string',
            'tanggal_mulai_pwkt' => 'nullable|date',
            'tanggal_berakhir_pwkt' => 'nullable|date',

            'rekening' => 'nullable|string',
            'nama_rekening' => 'nullable|string',
            'alamat' => 'nullable|string',

            'gaji_pokok' => 'nullable|numeric',
            'makan_transport' => 'nullable|numeric',
            'lembur' => 'nullable|numeric',
            'kehadiran' => 'nullable|numeric',
            'thr' => 'nullable|numeric',

            'bonus_pribadi' => 'nullable|numeric',
            'bonus_team' => 'nullable|numeric',
            'bonus_jackpot' => 'nullable|numeric',

            'izin' => 'nullable|numeric',
            'terlambat' => 'nullable|numeric',
            'mangkir' => 'nullable|numeric',
            'saldo_kasbon' => 'nullable|numeric',

            'tunjangan_bpjs_kesehatan' => 'nullable|numeric',
            'potongan_bpjs_kesehatan' => 'nullable|numeric',
            'tunjangan_bpjs_ketenagakerjaan' => 'nullable|numeric',
            'potongan_bpjs_ketenagakerjaan' => 'nullable|numeric',
            'tunjangan_pajak' => 'nullable|numeric',

            'izin_cuti' => 'nullable|integer',
            'izin_telat' => 'nullable|integer',
            'izin_lainnya' => 'nullable|integer',
            'izin_pulang_cepat' => 'nullable|integer',
        ]);

        // ========== UPLOAD FOTO ==========
        if ($request->hasFile('foto_karyawan')) {
            if ($pegawai->foto_karyawan) {
                Storage::disk('public')->delete($pegawai->foto_karyawan);
            }

            $pegawai->foto_karyawan =
                $request->file('foto_karyawan')->store('foto_karyawan', 'public');
        }

        // ========== UPDATE FIELD ==========
        $pegawai->update($validated);

        return response()->json([
            'message' => 'Data pegawai berhasil diperbarui',
            'pegawai' => $pegawai
        ]);
    }

    /**
     * DELETE PEGAWAI
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if ($pegawai->foto_karyawan) {
            Storage::disk('public')->delete($pegawai->foto_karyawan);
        }

        $pegawai->delete();

        return response()->json([
            'message' => 'Pegawai berhasil dihapus'
        ]);
    }

    /**
     * PROFILE PEGAWAI LOGIN
     */
    public function getProfilPegawai()
    {
        return response()->json(auth()->user());
    }

    /**
     * PROFILE (komplit + relasi)
     */
    public function myProfile()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $data = $user->toArray();

            // Tambahkan divisi (jika ada)
            $data['divisi'] = $user->divisi
                ? [
                    'id' => $user->divisi->id,
                    'nama' => $user->divisi->nama ?? null,
                ]
                : null;

            // Tambahkan lokasi (jika ada)
            $data['lokasi'] = $user->lokasi
                ? [
                    'id' => $user->lokasi->id,
                    'nama' => $user->lokasi->nama ?? null,
                ]
                : null;

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            \Log::error('myProfile error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: '.$e->getMessage()
            ], 500);
        }
    }

    public function rapatPegawai(Request $request)
    {
        $pegawai = auth()->user(); // login pegawai

        $rapats = Rapat::whereHas('pegawai', function ($q) use ($pegawai) {
            $q->where('pegawai_id', $pegawai->id);
        })
        ->orderBy('tanggal_rapat', 'desc')
        ->get();

        return response()->json($rapats);
    }

    // GET PROFILE UNTUK DASHBOARD ADMIN DAN SUPERADMIN
    public function getProfile()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Load relasi
            $user->load(['company', 'divisi', 'lokasi', 'role']);

            $data = $user->toArray();

            // Tambahkan nama relasi - SESUAIKAN dengan field yang benar
            $data['company_name'] = $user->company->name ?? null;
            $data['divisi_name'] = $user->divisi->nama ?? null;
            $data['status_pajak'] = $user->status_pajak ?? null;
            $data['ktp'] = $user->ktp ?? null;
            $data['kartu_keluarga'] = $user->kartu_keluarga ?? null;
            $data['bpjs_kesehatan'] = $user->bpjs_kesehatan ?? null;
            $data['bpjs_ketenagakerjaan'] = $user->bpjs_ketenagakerjaan ?? null;
            $data['npwp'] = $user->npwp ?? null;
            $data['sim'] = $user->sim ?? null;
            $data['no_pkwt'] = $user->no_pkwt ?? null;
            $data['no_kontrak'] = $user->no_kontrak ?? null;
            $data['rekening'] = $user->rekening ?? null;
            $data['nama_rekening'] = $user->nama_rekening ?? null;
            $data['izin_cuti'] = $user->izin_cuti ?? null;
            $data['izin_lainnya'] = $user->izin_lainnya ?? null;
            $data['izin_telat'] = $user->izin_telat ?? null;
            $data['izin_pulang_cepat'] = $user->izin_pulang_cepat ?? null;
            $data['gaji_pokok'] = $user->gaji_pokok ?? null;
            $data['makan_transport'] = $user->makan_transport ?? null;
            $data['kehadiran'] = $user->kehadiran ?? null;
            $data['lembur'] = $user->lembur ?? null;
            $data['thr'] = $user->thr ?? null;
            $data['bonus_pribadi'] = $user->bonus_pribadi ?? null;
            $data['bonus_team'] = $user->bonus_team ?? null;
            $data['bonus_jackpot'] = $user->bonus_jackpot ?? null;
            $data['izin'] = $user->izin ?? null;
            $data['terlambat'] = $user->terlambat ?? null;
            $data['mangkir'] = $user->mangkir ?? null;
            $data['saldo_kasbon'] = $user->saldo_kasbon ?? null;
            $data['tunjangan_bpjs_kesehatan'] = $user->tunjangan_bpjs_kesehatan ?? null;
            $data['tunjangan_bpjs_ketenagakerjaan'] = $user->tunjangan_bpjs_ketenagakerjaan ?? null;
            $data['potongan_bpjs_kesehatan'] = $user->potongan_bpjs_kesehatan ?? null;
            $data['potongan_bpjs_ketenagakerjaan'] = $user->potongan_bpjs_ketenagakerjaan ?? null;
            $data['tunjangan_pajak'] = $user->tunjangan_pajak ?? null;
            


            $data['lokasi_name'] = $user->lokasi->nama_lokasi ?? null;
            $data['role_name'] = $user->role->name ?? null;
            $data['tgl_lahir'] = $user->tgl_lahir ? $user->tgl_lahir->format('Y-m-d') : null;
            $data['tanggal_mulai_pwkt'] = $user->tanggal_mulai_pwkt ? $user->tanggal_mulai_pwkt->format('Y-m-d') : null;
            $data['tanggal_berakhir_pwkt'] = $user->tanggal_berakhir_pwkt ? $user->tanggal_berakhir_pwkt->format('Y-m-d') : null;
            $data['tgl_join'] = $user->tgl_join ? $user->tgl_join->format('Y-m-d') : null;
            $data['alamat'] = $user->alamat ?? null;

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            \Log::error('getProfile error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: '.$e->getMessage()
            ], 500);
        }
    }

}
