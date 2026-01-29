<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleDashboardMiddleware
{
    public function handle(Request $request, Closure $next, $requiredDashboard)
    {
        $pegawai = Auth::guard('sanctum')->user();

        if (!$pegawai) {
            return response()->json(['message' => 'Unauthorized - Pegawai not logged in'], 401);
        }

        // SUPERADMIN selalu bisa lewat untuk route apa pun yang diminta khusus dashboard_type
        if ($pegawai->dashboard_type === 'superadmin') {
            return $next($request);
        }

        // Kalau route ini butuh dashboard_type tertentu (misal: admin)
        if ($pegawai->dashboard_type !== $requiredDashboard) {
            return response()->json([
                'message' => "Access denied - hanya {$requiredDashboard} yang bisa mengakses route ini"
            ], 403);
        }

        // Untuk non-superadmin, wajib punya company_id
        if (!$pegawai->company_id) {
            return response()->json([
                'message' => 'Forbidden - Pegawai tidak terhubung ke perusahaan'
            ], 403);
        }

        return $next($request);
    }
}
