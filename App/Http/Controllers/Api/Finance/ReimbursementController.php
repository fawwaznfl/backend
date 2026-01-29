<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
use App\Models\KategoriReimbursement;
use App\Http\Requests\StoreReimbursementRequest;
use App\Http\Requests\UpdateReimbursementRequest;
use App\Helpers\ApiFormatter;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ReimbursementController extends Controller
{
    /* =====================================================
     * GET /reimbursement
     * ===================================================== */
    public function index(Request $request)
    {
        $pegawai = auth()->user(); // Pegawai
        $query = Reimbursement::with(['kategori', 'pegawai']);

        if ($pegawai->dashboard_type === 'admin') {
            $query->where('company_id', $pegawai->company_id);
        }

        if ($pegawai->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $pegawai->id);
        }

        if ($pegawai->dashboard_type === 'superadmin' && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return ApiFormatter::success($data, 'Data reimbursements');
    }

    /* =====================================================
     * GET /reimbursement/{id}
     * ===================================================== */
    public function show($id)
    {
        $pegawai = auth()->user();

        $query = Reimbursement::with(['kategori', 'pegawai'])
            ->where('id', $id);

        if ($pegawai->dashboard_type === 'admin') {
            $query->where('company_id', $pegawai->company_id);
        }

        if ($pegawai->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $pegawai->id);
        }

        $data = $query->first();

        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        return ApiFormatter::success($data, 'Detail reimbursement');
    }

    /* =====================================================
     * POST /reimbursement
     * ===================================================== */
    public function store(StoreReimbursementRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();

        if ($user->dashboard_type === 'pegawai') {
            // pegawai hanya boleh buat untuk dirinya sendiri
            $data['pegawai_id'] = $user->id;
            $data['company_id'] = $user->company_id;
        } else {
            // admin / superadmin
            $pegawai = \App\Models\Pegawai::findOrFail($request->pegawai_id);
            $data['pegawai_id'] = $pegawai->id;
            $data['company_id'] = $pegawai->company_id;
        }

        $kategori = KategoriReimbursement::where('id', $data['kategori_reimbursement_id'])
            ->where('company_id', $data['company_id'])
            ->firstOrFail();

        $data['total'] = $kategori->jumlah;
        $data['terpakai'] = $data['terpakai'] ?? 0;
        $data['sisa'] = $data['total'] - $data['terpakai'];

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('reimbursements', 'public');
        }

        if ($data['metode_reim'] !== 'transfer') {
            $data['no_rekening'] = null;
        }

        $reimbursement = Reimbursement::create($data);

        return ApiFormatter::success($reimbursement, 'Reimbursement berhasil dibuat');
    }


    /* =====================================================
     * PUT /reimbursement/{id}
     * ===================================================== */
    public function update(UpdateReimbursementRequest $request, $id)
    {
        $pegawai = auth()->user();

        $query = Reimbursement::where('id', $id);

        if ($pegawai->dashboard_type === 'admin') {
            $query->where('company_id', $pegawai->company_id);
        }

        if ($pegawai->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $pegawai->id);
        }

        $reimbursement = $query->first();

        if (!$reimbursement) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        $data = $request->validated();

        if ($request->kategori_reimbursement_id) {
            $kategori = KategoriReimbursement::where('id', $request->kategori_reimbursement_id)
                ->where('company_id', $reimbursement->company_id)
                ->firstOrFail();

            $data['total'] = $kategori->jumlah;
        }

        $terpakai = $data['terpakai'] ?? $reimbursement->terpakai;
        $total = $data['total'] ?? $reimbursement->total;
        $data['sisa'] = $total - $terpakai;

        if ($request->hasFile('file')) {
            if ($reimbursement->file) {
                @unlink(storage_path('app/public/' . $reimbursement->file));
            }
            $data['file'] = $request->file('file')->store('reimbursements', 'public');
        }

        $reimbursement->update($data);

        return ApiFormatter::success($reimbursement, 'Reimbursement berhasil diperbarui');
    }

    /* =====================================================
     * DELETE /reimbursement/{id}
     * ===================================================== */
    public function destroy($id)
    {
        $pegawai = auth()->user();

        $query = Reimbursement::where('id', $id);

        if ($pegawai->dashboard_type === 'admin') {
            $query->where('company_id', $pegawai->company_id);
        }

        if ($pegawai->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $pegawai->id);
        }

        $data = $query->first();

        if (!$data) {
            return ApiFormatter::error('Data tidak ditemukan', 404);
        }

        if ($data->file) {
            @unlink(storage_path('app/public/' . $data->file));
        }

        $data->delete();

        return ApiFormatter::success(null, 'Reimbursement berhasil dihapus');
    }

    /* =====================================================
     * REJECT / APPROVE
     * ===================================================== */
    public function approve(Request $request, $id)
    {
        $data = $this->getAuthorizedReimbursement($id);

        if ($request->hasFile('approved_file')) {
            $data->approved_file = $request->file('approved_file')
                ->store('approved-reimbursement', 'public');
        }

        $data->status = 'approve';
        $data->save();

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'reimbursement_approved',
                'title' => 'Pengajuan Reimbursement Disetujui',
                'message' => "Pengajuan reimbursement Anda sebesar Rp " . number_format($reimbursement->jumlah, 0, ',', '.') . " telah disetujui",
                'company_id' => $reimbursement->pegawai->company_id,
                'data' => [
                    'reimbursement_id' => $reimbursement->id,
                    'approved_by' => $user->name,
                    'jumlah' => $reimbursement->jumlah,
                ]
            ],
            [
                [
                    'user_id' => $reimbursement->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($data, 'Approved successfully');
    }

    public function reject($id)
    {
        $user = auth()->user();
        
        if (!in_array($user->dashboard_type, ['admin', 'superadmin'])) {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $reimbursement = Reimbursement::findOrFail($id);
        
        $reimbursement->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        //Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'reimbursement_rejected',
                'title' => 'Pengajuan Reimbursement Ditolak',
                'message' => "Pengajuan reimbursement Anda ditolak oleh {$user->name}",
                'company_id' => $reimbursement->pegawai->company_id,
                'data' => [
                    'reimbursement_id' => $reimbursement->id,
                    'rejected_by' => $user->name,
                ]
            ],
            [
                [
                    'user_id' => $reimbursement->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($reimbursement, 'Reimbursement ditolak');
    }

    /* =====================================================
     * HELPER
     * ===================================================== */
    private function changeStatus($id, $status)
    {
        $data = $this->getAuthorizedReimbursement($id);
        $data->status = $status;
        $data->save();

        return ApiFormatter::success($data, 'Status updated');
    }

    private function getAuthorizedReimbursement($id)
    {
        $pegawai = auth()->user();

        $query = Reimbursement::where('id', $id);

        if ($pegawai->dashboard_type === 'admin') {
            $query->where('company_id', $pegawai->company_id);
        }

        if ($pegawai->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $pegawai->id);
        }

        return $query->firstOrFail();
    }
}
