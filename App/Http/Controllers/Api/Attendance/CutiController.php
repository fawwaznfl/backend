<?php 

namespace App\Http\Controllers\Api\Attendance;

use App\Models\Cuti;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Cuti::with([
            'pegawai:id,name,company_id,lokasi_id',
            'pegawai.lokasi:id,nama_lokasi',
            'company:id,name',
            'approvedBy:id,name'
        ])->orderBy('created_at', 'desc');

        // ADMIN
        if ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        // PEGAWAI
        if ($user->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $user->id);
        }

        return ApiFormatter::success(
            $query->get(),
            'Data cuti berhasil diambil'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|integer',
            'pegawai_id' => 'required|integer',
            'jenis_cuti' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'alasan' => 'required|string',
            'foto' => 'nullable|image',
            'status' => 'nullable|string',
            'disetujui_oleh' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
            'catatan' => 'nullable|string',
        ]);

        // Upload file foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('cuti', 'public');
        }

        $cuti = Cuti::create($validated);

        return ApiFormatter::success($cuti, 'Data cuti berhasil dibuat');
    }

    public function show($id)
    {
        $cuti = Cuti::with([
            'pegawai:id,name,company_id,lokasi_id',
            'company:id,name',
            'approvedBy:id,name'
        ])->find($id);

        if (!$cuti) {
            return ApiFormatter::error('Data cuti tidak ditemukan', 404);
        }

        return ApiFormatter::success($cuti, 'Detail cuti berhasil diambil');
    }

    public function update(Request $request, $id)
    {
        $cuti = Cuti::find($id);

        if (!$cuti) {
            return ApiFormatter::error('Data cuti tidak ditemukan', 404);
        }

        // 🔒 Pegawai hanya boleh edit cuti sendiri
        if (auth()->user()->dashboard_type === 'pegawai') {
            if ($cuti->pegawai_id !== auth()->id()) {
                return ApiFormatter::error('Tidak diizinkan', 403);
            }
        }

        $validated = $request->validate([
            'jenis_cuti' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'alasan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        // 🖼️ Upload foto baru (hapus lama)
        if ($request->hasFile('foto')) {
            if ($cuti->foto && \Storage::disk('public')->exists($cuti->foto)) {
                \Storage::disk('public')->delete($cuti->foto);
            }

            $validated['foto'] = $request->file('foto')->store('cuti', 'public');
        }

        $validated['updated_by'] = auth()->id();

        $cuti->update($validated);

        return ApiFormatter::success($cuti, 'Data cuti berhasil diperbarui');
    }

    public function destroy($id)
    {
        $cuti = Cuti::find($id);

        if (!$cuti) {
            return ApiFormatter::error('Data cuti tidak ditemukan', 404);
        }

        $cuti->delete();
        
        return ApiFormatter::success(null, 'Data cuti berhasil dihapus');
    }

    // ✅ Approve - FIXED
    public function approve(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string'
        ]);

        // ✅ Definisikan $user di awal method
        $user = auth()->user();

        $cuti = Cuti::with('pegawai')->findOrFail($id);

        $cuti->status = 'disetujui';
        $cuti->disetujui_oleh = $user->id;
        $cuti->catatan = $request->catatan;
        $cuti->save();

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'cuti_approved',
                'title' => 'Pengajuan Cuti Disetujui',
                'message' => "Pengajuan cuti Anda telah disetujui oleh {$user->name}",
                'company_id' => $cuti->pegawai->company_id,
                'data' => [
                    'cuti_id' => $cuti->id,
                    'approved_by' => $user->name,
                    'tanggal_mulai' => $cuti->tanggal_mulai,
                    'tanggal_selesai' => $cuti->tanggal_selesai,
                ]
            ],
            [
                [
                    'user_id' => $cuti->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($cuti, 'Cuti berhasil disetujui');
    }

    // ✅ Reject - FIXED
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string'
        ]);

        // ✅ Definisikan $user di awal method
        $user = auth()->user();

        $cuti = Cuti::with('pegawai')->findOrFail($id);

        $cuti->status = 'ditolak';
        $cuti->disetujui_oleh = $user->id;
        $cuti->catatan = $request->catatan;
        $cuti->save();

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'cuti_rejected',
                'title' => 'Pengajuan Cuti Ditolak',
                'message' => "Pengajuan cuti Anda ditolak oleh {$user->name}",
                'company_id' => $cuti->pegawai->company_id,
                'data' => [
                    'cuti_id' => $cuti->id,
                    'rejected_by' => $user->name,
                ]
            ],
            [
                [
                    'user_id' => $cuti->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($cuti, 'Cuti berhasil ditolak');
    }

    public function checkToday($pegawai_id)
    {
        $today = now()->toDateString();

        $cuti = Cuti::where('pegawai_id', $pegawai_id)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->where('status', 'disetujui')
            ->first();

        if (!$cuti) {
            return ApiFormatter::success(null, 'Tidak ada cuti hari ini');
        }

        return ApiFormatter::success([
            'jenis_cuti' => $cuti->jenis_cuti,
            'status'     => $cuti->status,
        ], 'Pegawai sedang cuti');
    }

    public function summary($pegawai_id)
    {
        $pegawai = Pegawai::findOrFail($pegawai_id);

        $cutis = Cuti::where('pegawai_id', $pegawai_id)
            ->where('status', 'disetujui')
            ->get();

        // Hitung TERPAKAI
        $used = [
            'cuti' => 0,
            'izin_masuk' => 0,
            'izin_telat' => 0,
            'izin_pulang_cepat' => 0,
            'sakit' => 0,
        ];

        foreach ($cutis as $c) {
            $mulai = Carbon::parse($c->tanggal_mulai);
            $selesai = $c->tanggal_selesai
                ? Carbon::parse($c->tanggal_selesai)
                : $mulai;

            $totalHari = $mulai->diffInDays($selesai) + 1;

            switch ($c->jenis_cuti) {
                case 'cuti':
                    $used['cuti'] += $totalHari;
                    break;
                case 'izin_masuk':
                    $used['izin_masuk'] += $totalHari;
                    break;
                case 'izin_telat':
                    $used['izin_telat'] += $totalHari;
                    break;
                case 'izin_pulang_cepat':
                    $used['izin_pulang_cepat'] += $totalHari;
                    break;
                case 'sakit':
                    $used['sakit'] += $totalHari;
                    break;
            }
        }

        // HITUNG SISA
        $summary = [
            'cuti' => max(0, ($pegawai->izin_cuti ?? 0) - $used['cuti']),
            'izin_masuk' => max(0, ($pegawai->izin_lainnya ?? 0) - $used['izin_masuk']),
            'izin_telat' => max(0, ($pegawai->izin_telat ?? 0) - $used['izin_telat']),
            'izin_pulang_cepat' => max(0, ($pegawai->izin_pulang_cepat ?? 0) - $used['izin_pulang_cepat']),
            'sakit' => $used['sakit'],
        ];

        return ApiFormatter::success($summary, 'Ringkasan sisa izin');
    }
}