<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthPegawaiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:pegawais,username',
            'email' => 'required|string|email|unique:pegawais,email',
            'no_telp' => 'required|string|max:20',
            'password' => 'required|confirmed|min:6',
        ]);

        $pegawai = Pegawai::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'telepon' => $request->no_telp,
            'company_id' => $request->company_id,
            'password' => Hash::make($request->password),
            'tgl_join' => now(),
        ]);
        
        $token = $pegawai->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'pegawai' => $pegawai,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $pegawai = Pegawai::with([
            'lokasi:id,nama_lokasi'
        ])
        ->where('email', $request->email)
        ->first();


        if (! $pegawai || ! Hash::check($request->password, $pegawai->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah'],
            ]);
        }

        // CEK STATUS PEGAWAI
        if ($pegawai->status === 'inactive') {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda sudah tidak aktif. Silakan hubungi admin.'],
            ]);
        }

        $token = $pegawai->createToken('auth_token')->plainTextToken;

        if ($pegawai->foto_karyawan) {
            $pegawai->foto_karyawan = asset("storage/" . $pegawai->foto_karyawan);
    }

        $pegawai->nama_lokasi = $pegawai->lokasi?->nama_lokasi;

        return response()->json([
            'message' => 'Login sukses',
            'pegawai' => $pegawai,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function myProfile(Request $request)
    {
        return ApiFormatter::success($request->user());
    }

}
