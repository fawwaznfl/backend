<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Models\Lembur;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLemburRequest;
use App\Http\Requests\UpdateLemburRequest;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Lembur::with([
            'pegawai:id,name,company_id',
            'shift:id,nama',
            'approver:id,name'
        ]);

        // ===============================
        // SUPERADMIN
        // ===============================
        if ($user->dashboard_type === 'superadmin') {

            // optional filter company dari dropdown
            if ($request->company_id) {
                $query->where('company_id', $request->company_id);
            }
        }

        // ===============================
        // ADMIN
        // ===============================
        elseif ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        // ===============================
        // PEGAWAI
        // ===============================
        elseif ($user->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $user->id);
        }

        $data = $query
            ->orderBy('tanggal_lembur', 'desc')
            ->get();

        return ApiFormatter::success($data, 'Absensi fetched');
    }

    public function store(StoreLemburRequest $request)
    {
        $user = auth()->user();
        $payload = $request->validated();

        // Inject data otomatis
        $payload['company_id'] = $user->company_id;
        $payload['created_by'] = $user->id;

        $absensi = Lembur::create($payload);

        return ApiFormatter::success($absensi, 'Absensi created', 201);
    }

    public function show($id)
    {
        $absensi = Lembur::with(['pegawai'])->find($id);

        if (!$absensi) {
            return ApiFormatter::error('Absensi not found', 404);
        }

        return ApiFormatter::success($absensi, 'Absensi found');
    }

    public function update(UpdateLemburRequest $request, $id)
    {
        $absensi = Lembur::find($id);
        if (!$absensi) {
            return ApiFormatter::error('Absensi not found', 404);
        }

        $payload = $request->validated();
        $payload['updated_by'] = auth()->id();

        $absensi->update($payload);

        return ApiFormatter::success($absensi, 'Absensi updated');
    }

    public function destroy($id)
    {
        $absensi = Lembur::find($id);
        if (!$absensi) {
            return ApiFormatter::error('Absensi not found', 404);
        }

        $absensi->delete();

        return ApiFormatter::success(null, 'Absensi deleted', 204);
    }

    public function autoAbsen(Request $request)
    {
        $request->validate([
            'tanggal_lembur' => 'required|date',
            'lokasi' => 'required',
            'foto' => 'required|file|mimes:jpg,jpeg,png',
        ]);

        $user = auth()->user();
        $pegawaiId = $user->id;

        // cek absen hari ini
        $absen = Lembur::where('pegawai_id', $pegawaiId)
            ->where('tanggal_lembur', $request->tanggal_lembur)
            ->first();

        $jamSekarang = now()->format('H:i:s');
        $fotoPath = $request->file('foto')->store('lembur', 'public');

        // =========================
        // ABSEN MASUK LEMBUR
        // =========================
        if (!$absen) {

            Lembur::create([
                'pegawai_id'     => $pegawaiId,
                'company_id'     => $user->company_id,
                'tanggal_lembur' => $request->tanggal_lembur,
                'jam_mulai'      => $jamSekarang,
                'lokasi_masuk'   => $request->lokasi,
                'foto_masuk'     => $fotoPath,
                'status'         => 'menunggu',
                'created_by'     => $user->id,
            ]);

            return ApiFormatter::success(null, 'Absen masuk lembur berhasil');
        }

        // =========================
        // ABSEN PULANG LEMBUR
        // =========================
        if (!$absen->jam_selesai) {

            $jamMulai = Carbon::createFromFormat('H:i:s', $absen->jam_mulai);
            $jamSelesai = Carbon::createFromFormat('H:i:s', $jamSekarang);

            // ⏱ hitung selisih menit (dibulatkan ke atas)
            $durasiMenit = (int) ceil($jamMulai->diffInSeconds($jamSelesai) / 60);

            // 🔄 konversi ke JAM desimal (decimal(5,2))
            $totalLemburJam = round($durasiMenit / 60, 2);

            $absen->update([
                'jam_selesai'   => $jamSekarang,
                'total_lembur_menit' => $durasiMenit,
                'lokasi_pulang' => $request->lokasi,
                'foto_pulang'   => $fotoPath,
                'updated_by'    => $user->id,
            ]);


            return ApiFormatter::success(null, 'Absen pulang lembur berhasil');
        }

        return ApiFormatter::error(
            'Anda sudah absen masuk & pulang lembur hari ini',
            400
        );
    }

    public function approve($id)
    {
        $user = auth()->user();

        $lembur = Lembur::find($id);
        if (!$lembur) {
            return ApiFormatter::error('Lembur not found', 404);
        }

        $lembur->update([
            'status'      => 'disetujui',
            'disetujui_oleh' => $user->id,
            'updated_by'  => $user->id,
        ]);

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'lembur_approved',
                'title' => 'Pengajuan Lembur Disetujui',
                'message' => "Pengajuan lembur Anda telah disetujui oleh {$user->name}",
                'company_id' => $lembur->pegawai->company_id,
                'data' => [
                    'lembur_id' => $lembur->id,
                    'approved_by' => $user->name,
                    'tanggal' => $lembur->tanggal,
                    'durasi' => $lembur->durasi,
                ]
            ],
            [
                [
                    'user_id' => $lembur->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($lembur, 'Lembur disetujui');
    }

    public function reject($id)
    {
        $user = auth()->user();

        $lembur = Lembur::find($id);
        if (!$lembur) {
            return ApiFormatter::error('Lembur not found', 404);
        }

        $lembur->update([
            'status'      => 'ditolak',
            'disetujui_oleh' => $user->id,
            'updated_by'  => $user->id,
        ]);

        // ✅ Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'lembur_rejected',
                'title' => 'Pengajuan Lembur Ditolak',
                'message' => "Pengajuan lembur Anda ditolak oleh {$user->name}",
                'company_id' => $lembur->pegawai->company_id,
                'data' => [
                    'lembur_id' => $lembur->id,
                    'rejected_by' => $user->name,
                ]
            ],
            [
                [
                    'user_id' => $lembur->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($lembur, 'Lembur ditolak');
    }

    public function byPegawai($pegawaiId)
    {
        $data = Lembur::where('pegawai_id', $pegawaiId)
            ->orderBy('tanggal_lembur', 'desc')
            ->get()
            ->map(function ($a) {

                if (!$a->jam_mulai) {
                    $status = 'belum_mulai';
                } elseif ($a->jam_mulai && !$a->jam_selesai) {
                    $status = 'sedang_lembur';
                } else {
                    $status = $a->status; // menunggu | disetujui | ditolak
                }

                return [
                    'id'          => $a->id,
                    'tanggal_lembur'     => $a->tanggal_lembur,
                    'jam_mulai'   => $a->jam_mulai,
                    'jam_selesai' => $a->jam_selesai,
                    'status'      => $status,
                ];
            });

        return ApiFormatter::success($data, 'Data lembur pegawai');
    }
}
