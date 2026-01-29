<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyScopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $pegawai = Auth::guard('sanctum')->user();

        if (!$pegawai) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // superadmin bebas
        if ($pegawai->dashboard_type === 'superadmin') {
            return $next($request);
        }

        // selain superadmin wajib punya company_id
        if (!$pegawai->company_id) {
            return response()->json(['message' => 'Forbidden - No company'], 403);
        }

        // inject company_id ke request
        $request->merge(['company_id' => $pegawai->company_id]);

        return $next($request);
    }
}
