<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\DinasLuarMapping;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DinasLuarMappingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $pegawai_id = $request->pegawai_id;

        // ========================
        // 1. Pegawai → hanya lihat dirinya
        // ========================
        if ($user->dashboard_type === 'pegawai') {
            $data = DinasLuarMapping::with('shift:id,nama,jam_masuk,jam_pulang')
                ->where('pegawai_id', $user->id)
                ->orderBy('tanggal_mulai', 'asc')
                ->get();

            return ApiFormatter::success($data);
        }

        // ========================
        // 2. Admin & Superadmin
        // ========================
        $query = DinasLuarMapping::with('shift:id,nama,jam_masuk,jam_pulang');

        if ($pegawai_id) {
            $query->where('pegawai_id', $pegawai_id);
        }

        // Admin → wajib filter company
        if ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        $data = $query->orderBy('tanggal_mulai', 'asc')->get();

        return ApiFormatter::success($data);
    }


    // ======================================
    // STORE — Buat shift mapping per hari
    // ======================================
    public function store(Request $request)
    {
        $user = auth()->user();

        // Pegawai hanya boleh pending untuk dirinya sendiri
        if ($user->dashboard_type === 'pegawai') {
            $request->merge([
                'pegawai_id' => $user->id,
                'company_id' => $user->company_id, // kalau ada
            ]);
        }

        // Validasi
        $request->validate([
            'pegawai_id' => 'required',
            'shift_id' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // Admin wajib kirim company_id
        if ($user->dashboard_type !== 'pegawai') {
            $request->validate([
                'company_id' => 'required',
            ]);
        }

        $start = Carbon::parse($request->tanggal_mulai);
        $end   = Carbon::parse($request->tanggal_selesai);

        $inserted = [];

        while ($start->lte($end)) {
            
            $exists = DinasLuarMapping::where('pegawai_id', $request->pegawai_id)
                ->where('tanggal_mulai', $start->format('Y-m-d'))
                ->first();

            if ($exists) {
                return ApiFormatter::error(
                    "Tanggal {$start->format('Y-m-d')} sudah ditambahkan sebelumnya",
                    422
                );
            }

            $inserted[] = DinasLuarMapping::create([
                'pegawai_id'     => $request->pegawai_id,
                'company_id'     => $request->company_id,
                'shift_id'       => $request->shift_id,
                'tanggal_mulai'  => $start->format('Y-m-d'),
                'tanggal_selesai'=> $end->format('Y-m-d'),
            ]);

            $start->addDay();
        }

        return ApiFormatter::success(
            $inserted,
            $user->dashboard_type === 'pegawai'
                ? 'pending berhasil, menunggu persetujuan admin.'
                : 'Shift berhasil ditambahkan.'
        );
    }



    // ============================
    // DELETE
    // ============================
    public function destroy($id)
    {
        $data = DinasLuarMapping::findOrFail($id);
        $data->delete();
        return ApiFormatter::success(null, 'Mapping deleted');
    }


    // ============================
    // Pegawai melihat shift sendiri
    // ============================

    public function myById($id)
    {
        $user = auth()->user();

        // Pegawai hanya boleh ambil shift miliknya sendiri
        if ($user->dashboard_type === 'pegawai' && $user->id != $id) {
            return response()->json([
                'message' => 'Access denied - pegawai hanya bisa melihat shift miliknya sendiri'
            ], 403);
        }

        $data = DinasLuarMapping::with('shift')
            ->where('pegawai_id', $id)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        return ApiFormatter::success($data);
    }

    public function show($id)
    {
        $user = auth()->user();

        // Ambil data shift mapping
        $mapping = DinasLuarMapping::with('shift')->find($id);

        if (!$mapping) {
            return ApiFormatter::error('Data not found', 404);
        }

        // Jika dashboard_type admin / superadmin -> boleh lihat semuanya
        if (in_array($user->dashboard_type, ['admin', 'superadmin'])) {
            return ApiFormatter::success($mapping, 'Success');
        }

        // Jika bukan admin/superadmin -> hanya boleh lihat data milik dirinya sendiri
        if ($mapping->pegawai_id != $user->id) {
            return ApiFormatter::error('Unauthorized', 403);
        }

        return ApiFormatter::success($mapping, 'Success');
    }

    public function ByPegawai($pegawai_id)
    {
        $user = auth()->user();

        // Non-admin hanya boleh lihat dirinya sendiri
        if (!in_array($user->dashboard_type, ['admin', 'superadmin'])) {
            if ($user->id != $pegawai_id) {
                return ApiFormatter::error('Unauthorized', 403);
            }
        }


        $data = DinasLuarMapping::with('shift')
            ->where('pegawai_id', $pegawai_id)
            ->get();

        if ($data->isEmpty()) {
            return ApiFormatter::error('Data not found', 404);
        }

        return ApiFormatter::success($data, 'Success');
    }

    public function showByPegawai($pegawai_id)
    {
        $authUser = auth()->user();

        // Jika pegawai, hanya boleh lihat datanya sendiri
        if ($authUser->dashboard_type === 'pegawai' && $authUser->id != $pegawai_id) {
            return response()->json([
                'message' => 'Access denied - anda hanya bisa melihat shift anda sendiri'
            ], 403);
        }

        $data = DinasLuarMapping::with('shift')
            ->where('pegawai_id', $pegawai_id)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function myShift(Request $request)
    {
        $user = $request->user();

        // Jika bukan pegawai, tolak
        if ($user->dashboard_type !== 'pegawai') {
            return response()->json([
                'message' => 'Access denied - hanya pegawai yang bisa mengakses route ini'
            ], 403);
        }

        // Ambil shift berdasarkan pegawai_id milik user login
        $data = DinasLuarMapping::with([
            'shift:id,nama,jam_masuk,jam_pulang'
        ])
        ->where('pegawai_id', $user->id)
        ->orderBy('tanggal_mulai', 'asc')
        ->get();

        return ApiFormatter::success($data, 'Dinas Luar pegawai berhasil diambil');
    }

    public function approve($id)
    {
        $user = auth()->user();

        if ($user->dashboard_type === 'pegawai') {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $mapping = DinasLuarMapping::findOrFail($id);

        $mapping->status = 'approved';
        $mapping->approved_by = $user->id;
        $mapping->approved_at = now();
        $mapping->save();

        return ApiFormatter::success($mapping, 'Shift berhasil disetujui');
    }

    public function reject($id)
    {
        $user = auth()->user();

        if ($user->dashboard_type === 'pegawai') {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $mapping = DinasLuarMapping::findOrFail($id);

        $mapping->status = 'rejected';
        $mapping->approved_by = $user->id;
        $mapping->approved_at = now();
        $mapping->save();

        return ApiFormatter::success($mapping, 'Shift berhasil ditolak');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->dashboard_type === 'pegawai') {
            return ApiFormatter::error('Unauthorized', 403);
        }

        // Ambil data mapping
        $mapping = DinasLuarMapping::find($id);

        if (!$mapping) {
            return ApiFormatter::error('Data not found', 404);
        }

        // Validasi
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // Update langsung
        $mapping->update([
            'shift_id'        => $validated['shift_id'],
            'tanggal_mulai'   => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
        ]);

        return ApiFormatter::success($mapping, 'Shift berhasil diperbarui');
    }


    public function today($pegawai_id)
    {
        $today = date('Y-m-d');

        $shift = DinasLuarMapping::with('shift')
            ->where('pegawai_id', $pegawai_id)
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->orderBy('id', 'desc') // ambil yang terbaru
            ->first();

        return ApiFormatter::success($shift, 'Shift hari ini');
    }

}
