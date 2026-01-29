<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class DashboardAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $dashboardType)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Ambil pegawai berdasarkan email user
        $pegawai = Pegawai::where('email', $user->email)->first();

        // Jika tidak ada pegawai, asumsi pegawai biasa
        $currentType = $pegawai->dashboard_type ?? 'pegawai';

        // Cek apakah dashboard sesuai
        if ($currentType !== $dashboardType) {
            return response()->json([
                'message' => 'Access denied. This area is for ' . $dashboardType . ' only.'
            ], 403);
        }

        return $next($request);
    }
}
