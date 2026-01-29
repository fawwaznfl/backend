<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPegawaiStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika user adalah pegawai dan statusnya inactive
        if ($user && $user->status === 'inactive') {
            return response()->json([
                'message' => 'Akun Anda sudah tidak aktif. Silakan hubungi admin.'
            ], 403);
        }

        return $next($request);
    }
}