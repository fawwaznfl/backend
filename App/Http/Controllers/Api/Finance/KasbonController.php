<?php

namespace App\Http\Controllers\Api\Finance;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Kasbon;
use App\Models\Pegawai;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Kasbon::with([
            'pegawai:id,name',
            'company:id,name'
        ]);

        // SUPERADMIN
        if ($user->dashboard_type === 'superadmin') {

            // optional filter company dari dropdown
            if ($request->company_id) {
                $query->where('company_id', $request->company_id);
            }
        }

        // ADMIN
        elseif ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        // PEGAWAI
        elseif ($user->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $user->id);
        }

        $data = $query
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'metode_pengiriman' => 'required|in:cash,transfer',
            'nomor_rekening' => 'nullable|required_if:metode_pengiriman,transfer|string',
            'nominal' => 'required|numeric',
            'keperluan' => 'required|string',
            'status' => 'nullable|string|in:pending,approve,reject,paid',
        ]);

        $validated['status'] = 'pending';
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $pegawai = Pegawai::findOrFail($validated['pegawai_id']);

        $kasbonInfo = $this->hitungSisaKasbon($pegawai);

        if ($validated['nominal'] > $kasbonInfo['sisa']) {
            return response()->json([
                'message' => 'Nominal kasbon melebihi sisa limit',
                'limit' => $kasbonInfo['limit'],
                'terpakai' => $kasbonInfo['terpakai'],
                'sisa' => $kasbonInfo['sisa'],
            ], 422);
        }


        $kasbon = Kasbon::create($validated);

        return response()->json(['message' => 'Kasbon dibuat', 'data' => $kasbon]);
    }

    // Show detail
    public function show($id)
    {
        $kasbon = Kasbon::with(['pegawai', 'company'])->findOrFail($id);
        return response()->json(['data' => $kasbon]);
    }

    // Update
    public function updateStatus(Request $request, $id)
    {
        $kasbon = Kasbon::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approve,reject,paid'
        ]);

        $kasbon->update([
            'status' => $validated['status'],
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Status updated']);
    }

    public function approve($id)
    {
        $user = auth()->user();
        
        if (!in_array($user->dashboard_type, ['admin', 'superadmin'])) {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $kasbon = Kasbon::findOrFail($id);
        
        $kasbon->update([
            'status' => 'approve',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'kasbon_approved',
                'title' => 'Pengajuan Kasbon Disetujui',
                'message' => "Pengajuan kasbon Anda sebesar Rp " . number_format($kasbon->jumlah, 0, ',', '.') . " telah disetujui",
                'company_id' => $kasbon->pegawai->company_id,
                'data' => [
                    'kasbon_id' => $kasbon->id,
                    'approved_by' => $user->name,
                    'jumlah' => $kasbon->jumlah,
                ]
            ],
            [
                [
                    'user_id' => $kasbon->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($kasbon, 'Kasbon berhasil disetujui');
    }

    public function reject($id)
    {
        $user = auth()->user();
        
        if (!in_array($user->dashboard_type, ['admin', 'superadmin'])) {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $kasbon = Kasbon::findOrFail($id);
        
        $kasbon->update([
            'status' => 'reject',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'kasbon_rejected',
                'title' => 'Pengajuan Kasbon Ditolak',
                'message' => "Pengajuan kasbon Anda ditolak oleh {$user->name}",
                'company_id' => $kasbon->pegawai->company_id,
                'data' => [
                    'kasbon_id' => $kasbon->id,
                    'rejected_by' => $user->name,
                ]
            ],
            [
                [
                    'user_id' => $kasbon->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($kasbon, 'Kasbon ditolak');
    }


    // Delete
    public function destroy($id)
    {
        $kasbon = Kasbon::findOrFail($id);
        $kasbon->delete();

        return response()->json(['message' => 'Kasbon dihapus']);
    }

    public function approval(Request $request, $id)
    {
        $kasbon = Kasbon::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approve,reject',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // File TIDAK wajib
        $filePath = $kasbon->file_approve; // tetap pakai file lama jika tidak upload

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('kasbon', 'public');
        }

        $kasbon->update([
            'status' => $validated['status'],
            'file_approve' => $filePath,  // bisa null
            'disetujui_oleh' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Kasbon berhasil diperbarui.',
            'data' => $kasbon
        ]);
    }

    public function update(Request $request, $id)
    {
        $kasbon = Kasbon::findOrFail($id);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keperluan' => 'required|string',
            'metode_pengiriman' => 'required|in:cash,transfer',
            'nomor_rekening' => 'nullable|required_if:metode_pengiriman,transfer|string|max:100',
        ]);

        // kalau cash → nomor rekening null
        if ($validated['metode_pengiriman'] === 'cash') {
            $validated['nomor_rekening'] = null;
        }

        $validated['updated_by'] = Auth::id();

        $kasbon->update($validated);

        return response()->json([
            'message' => 'Kasbon berhasil diperbarui',
            'data' => $kasbon
        ]);
    }

    public function bayar(Request $request, $id)
    {
        $kasbon = Kasbon::findOrFail($id);

        if ($kasbon->status !== 'approve') {
            return response()->json(['message' => 'Kasbon belum disetujui'], 422);
        }

        if ($request->hasFile('file_approve')) {
            $path = $request->file('file_approve')->store('kasbon', 'public');
            $kasbon->file_approve = $path;
        }

        $kasbon->status = 'paid';
        $kasbon->save();

        return response()->json(['message' => 'Kasbon dibayar']);
    }

    private function hitungSisaKasbon(Pegawai $pegawai)
    {
        $terpakai = Kasbon::where('pegawai_id', $pegawai->id)
            ->where('status', 'approve')
            ->sum('nominal');


        return [
            'limit' => $pegawai->saldo_kasbon,
            'terpakai' => $terpakai,
            'sisa' => max($pegawai->saldo_kasbon - $terpakai, 0),
        ];
    }

    public function sisaKasbon()
    {
        $pegawai = auth()->user();

        $data = $this->hitungSisaKasbon($pegawai);

        return response()->json($data);
    }

}
