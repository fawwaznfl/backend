<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // Step 1: cek email
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $pegawai = Pegawai::where('email', $request->email)->first();

        if (!$pegawai) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        // Kalau email ada, return sukses
        return response()->json(['message' => 'Email valid']);
    }

    // Step 2: reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed' // password + password_confirmation
        ]);

        $pegawai = Pegawai::where('email', $request->email)->first();

        if (!$pegawai) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        $pegawai->password = Hash::make($request->password);
        $pegawai->save();

        return response()->json(['message' => 'Password berhasil diubah']);
    }
}