<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class ProfileController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 🔑 ambil token
        $token = $request->bearerToken();

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return ApiFormatter::error('Unauthorized', 401);
        }

        // INI pegawai yang login
        $user = $accessToken->tokenable;

        // password lama salah
        if (!Hash::check($request->current_password, $user->password)) {
            return ApiFormatter::error(
                'Password lama tidak sesuai',
                422
            );
        }

        // ✅ update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ApiFormatter::success(
            null,
            'Password berhasil diperbarui'
        );
    }

    public function profile(Request $request)
    {
        $token = $request->bearerToken();
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return ApiFormatter::error('Unauthorized', 401);
        }

        $pegawai = $accessToken->tokenable
            ->load(['divisi', 'lokasi']);

        return ApiFormatter::success(
            $pegawai,
            'Profile berhasil diambil'
        );
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto_karyawan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // AUTH USER = PEGAWAI
        $pegawai = auth()->user();

        if (!$pegawai) {
            return response()->json([
                'message' => 'Pegawai tidak terautentikasi'
            ], 401);
        }

        // (optional) hapus foto lama
        if ($pegawai->foto_karyawan) {
            Storage::disk('public')->delete($pegawai->foto_karyawan);
        }

        // simpan foto baru
        $path = $request->file('foto_karyawan')
            ->store('foto_karyawan', 'public');

        // update langsung
        $pegawai->update([
            'foto_karyawan' => $path
        ]);

        return response()->json([
            'message' => 'Foto profile berhasil diupdate',
            'data' => $pegawai
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'telepon'   => 'nullable|string|max:20',
            'tgl_lahir' => 'nullable|date',
            'alamat'    => 'nullable|string',
            'gender'    => 'nullable|in:Laki-laki,Perempuan',
            'status_nikah'  => 'nullable|in:TK/0,TK/1,TK/2,TK/3,K0,K1,K2,K3',
            'ktp'        => 'nullable|digits:16',
            'kartu_keluarga'        => 'nullable|digits:16',

        ]);

        $pegawai = Auth::user();

        if ($request->filled('telepon')) {
            $pegawai->telepon = $request->telepon;
        }

        if ($request->filled('tgl_lahir')) {
            $pegawai->tgl_lahir = $request->tgl_lahir;
        }

        if ($request->filled('alamat')) {
            $pegawai->alamat = $request->alamat;
        }

        if ($request->filled('nama_rekening')) {
            $pegawai->nama_rekening = $request->nama_rekening;
        }

        if ($request->filled('gender')) {
            $pegawai->gender = $request->gender;
        }

        if ($request->filled('status_nikah')) {
            $pegawai->status_nikah = $request->status_nikah;
        }

        if ($request->filled('ktp')) {
            $pegawai->ktp = $request->ktp;
        }

        if ($request->filled('kartu_keluarga')) {
            $pegawai->kartu_keluarga = $request->kartu_keluarga;
        }

        if ($request->filled('bpjs_kesehatan')) {
            $pegawai->bpjs_kesehatan = $request->bpjs_kesehatan;
        }

        if ($request->filled('bpjs_ketenagakerjaan')) {
            $pegawai->bpjs_ketenagakerjaan = $request->bpjs_ketenagakerjaan;
        }

        if ($request->filled('npwp')) {
            $pegawai->npwp = $request->npwp;
        }

        if ($request->filled('sim')) {
            $pegawai->sim = $request->sim;
        }

        if ($request->filled('no_pkwt')) {
            $pegawai->no_pkwt = $request->no_pkwt;
        }

        if ($request->filled('no_kontrak')) {
            $pegawai->no_kontrak = $request->no_kontrak;
        }

        if ($request->filled('rekening')) {
            $pegawai->rekening = $request->rekening;
        }

        // SAVE SEKALI DI AKHIR
        $pegawai->save();

        return response()->json([
            'message' => 'Profile berhasil diperbarui',
        ]);
    }

}
