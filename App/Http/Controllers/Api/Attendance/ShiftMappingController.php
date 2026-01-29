<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Models\ShiftMapping;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\Shift;
use Carbon\Carbon;
use App\Services\NotificationService;

 
class ShiftMappingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $pegawai_id = $request->pegawai_id;
        $today = Carbon::today()->toDateString();

        // ==========================
        // PEGAWAI
        // ==========================
        if ($user->dashboard_type === 'pegawai') {

            $query = ShiftMapping::with('shift:id,nama,jam_masuk,jam_pulang')
                ->where('pegawai_id', $user->id);

            //$this->shiftMasihAktifQuery($query, $today); // ini untuk tidak menampilkan yang sudah berlalu

            $data = $query
                ->orderBy('tanggal_mulai', 'asc')
                ->get();

            return ApiFormatter::success($data);
        }

        // ==========================
        // ADMIN & SUPERADMIN
        // ==========================
        $query = ShiftMapping::with('shift:id,nama,jam_masuk,jam_pulang');

        if ($pegawai_id) {
            $query->where('pegawai_id', $pegawai_id);
        }

        if ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        $this->shiftMasihAktifQuery($query, $today);

        $data = $query
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        return ApiFormatter::success($data);
    }


    // STORE — Buat shift mapping per hari
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'shift_id' => 'required|exists:shifts,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // 🔥 AMBIL COMPANY DARI PEGAWAI
        $pegawai = Pegawai::findOrFail($request->pegawai_id);

        $companyId = $pegawai->company_id;

        $start = Carbon::parse($request->tanggal_mulai);
        $end   = Carbon::parse($request->tanggal_selesai);

        $inserted = [];

        while ($start->lte($end)) {

            $exists = ShiftMapping::where('pegawai_id', $pegawai->id)
                ->where('tanggal_mulai', $start->format('Y-m-d'))
                ->first();

            if ($exists) {
                return ApiFormatter::error(
                    "Tanggal {$start->format('Y-m-d')} sudah ditambahkan",
                    422
                );
            }

            $inserted[] = ShiftMapping::create([
                'pegawai_id'    => $pegawai->id,
                'company_id'    => $companyId, 
                'shift_id'      => $request->shift_id,
                'tanggal_mulai' => $start->format('Y-m-d'),
                'tanggal_selesai' => $end->format('Y-m-d'),
                'toleransi_telat' => $request->toleransi_telat ?? 0,
                // 🔥 DEFAULT SHIFT NORMAL
                'status'          => null,
                'requested_by'    => null,
                'approved_by'     => null,
                'approved_at'     => null,
            ]);

            $start->addDay();
        }

        return ApiFormatter::success(
            $inserted,
            'Shift berhasil ditambahkan'
        );
    }

    // DELETE
    public function destroy($id)
    {
        $data = ShiftMapping::findOrFail($id);
        $data->delete();
        return ApiFormatter::success(null, 'Mapping deleted');
    }

    // Pegawai melihat shift sendiri
    public function myById($id)
    {
        $user = auth()->user();

        // Pegawai hanya boleh ambil shift miliknya sendiri
        if ($user->dashboard_type === 'pegawai' && $user->id != $id) {
            return response()->json([
                'message' => 'Access denied - pegawai hanya bisa melihat shift miliknya sendiri'
            ], 403);
        }

        $data = ShiftMapping::with('shift')
            ->where('pegawai_id', $id)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        return ApiFormatter::success($data);
    }

    public function show($id)
    {
        $user = auth()->user();

        // Ambil data shift mapping
        $mapping = ShiftMapping::with('shift')->find($id);

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
        $today = Carbon::today()->toDateString();

        // Non-admin hanya boleh lihat dirinya sendiri
        if (!in_array($user->dashboard_type, ['admin', 'superadmin'])) {
            if ($user->id != $pegawai_id) {
                return ApiFormatter::error('Unauthorized', 403);
            }
        }

        $query = ShiftMapping::with('shift:id,nama,jam_masuk,jam_pulang')
            ->where('pegawai_id', $pegawai_id);

        $this->shiftMasihAktifQuery($query, $today);

        $data = $query
            ->orderBy('tanggal_mulai', 'asc')
            ->get();


        if ($data->isEmpty()) {
            return ApiFormatter::error('Data not found', 404);
        }

        return ApiFormatter::success($data, 'Success');
    }

    public function showByPegawai($pegawai_id)
    {
        $authUser = auth()->user();
        $today = Carbon::today()->toDateString();

        // Pegawai hanya boleh lihat datanya sendiri
        if ($authUser->dashboard_type === 'pegawai' && $authUser->id != $pegawai_id) {
            return response()->json([
                'message' => 'Access denied - anda hanya bisa melihat shift anda sendiri'
            ], 403);
        }

        $query = ShiftMapping::with('shift:id,nama,jam_masuk,jam_pulang')
            ->where('pegawai_id', $pegawai_id); 

        $this->shiftMasihAktifQuery($query, $today);

        $data = $query
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        return ApiFormatter::success($data, 'Success');
    }

    public function myShift(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        // Jika bukan pegawai, tolak
        if ($user->dashboard_type !== 'pegawai') {
            return response()->json([
                'message' => 'Access denied - hanya pegawai yang bisa mengakses route ini'
            ], 403);
        }

        // Ambil shift berdasarkan pegawai_id milik user login
        $query = ShiftMapping::with('shift:id,nama,jam_masuk,jam_pulang')
            ->where('pegawai_id', $user->id);

        $this->shiftMasihAktifQuery($query, $today);

        $data = $query
            ->orderBy('tanggal_mulai', 'asc')
            ->get();


        return ApiFormatter::success($data, 'Shift pegawai berhasil diambil');
    }

    public function requests(Request $request)
    {
        $user = auth()->user(); // ✅ FIX INI

        \Log::info('HIT requests()', [
            'user' => $user,
            'token' => $request->bearerToken(),
        ]);

        if (!in_array($user->dashboard_type, ['admin', 'superadmin'])) {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $query = ShiftMapping::with([
            'pegawai:id,name,company_id',
            'shift:id,nama,jam_masuk,jam_pulang',
            'shiftLama:id,nama,jam_masuk,jam_pulang',
        ])
        ->whereNotNull('requested_by')
        ->where('status', 'pending');

        if ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        $data = $query
            ->orderBy('tanggal_mulai', 'asc')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'tanggal_mulai' => $m->tanggal_mulai,
                    'status' => $m->status,
                    'company_id' => $m->company_id,
                    'pegawai' => [
                        'id' => $m->pegawai->id,
                        'name' => $m->pegawai->name,
                    ],
                    'shift_lama' => $m->shiftLama ? [
                        'nama' => $m->shiftLama->nama,
                        'jam_masuk' => $m->shiftLama->jam_masuk,
                        'jam_pulang' => $m->shiftLama->jam_pulang,
                    ] : null,
                    'shift_baru' => [
                        'nama' => $m->shift->nama,
                        'jam_masuk' => $m->shift->jam_masuk,
                        'jam_pulang' => $m->shift->jam_pulang,
                    ],
                ];
            });

        return ApiFormatter::success($data, 'List request shift');
    }



    public function approve($id)
    {
        $user = auth()->user();

        if ($user->dashboard_type === 'pegawai') {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $mapping = ShiftMapping::with(['pegawai', 'shift'])->findOrFail($id);

        $mapping->status = 'approved';
        $mapping->approved_by = $user->id;
        $mapping->approved_at = now();
        $mapping->save();

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'request_shift_approved',
                'title' => 'Request Shift Disetujui',
                'message' => "Request perubahan shift Anda pada {$mapping->tanggal_mulai} telah disetujui",
                'company_id' => $mapping->company_id,
                'data' => [
                    'shift_mapping_id' => $mapping->id,
                    'approved_by' => $user->name,
                    'tanggal' => $mapping->tanggal_mulai,
                    'shift_nama' => $mapping->shift->nama,
                ]
            ],
            [
                [
                    'user_id' => $mapping->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($mapping, 'Shift berhasil disetujui');
    }

    public function reject($id)
    {
        $user = auth()->user();

        if ($user->dashboard_type === 'pegawai') {
            return ApiFormatter::error('Unauthorized', 403);
        }

        $mapping = ShiftMapping::with('pegawai')->findOrFail($id);

        $mapping->status = 'rejected';
        $mapping->approved_by = $user->id;
        $mapping->approved_at = now();
        $mapping->save();

        // Kirim notifikasi ke pegawai
        NotificationService::create(
            [
                'type' => 'request_shift_rejected',
                'title' => 'Request Shift Ditolak',
                'message' => "Request perubahan shift Anda pada {$mapping->tanggal_mulai} ditolak",
                'company_id' => $mapping->company_id,
                'data' => [
                    'shift_mapping_id' => $mapping->id,
                    'rejected_by' => $user->name,
                    'tanggal' => $mapping->tanggal_mulai,
                ]
            ],
            [
                [
                    'user_id' => $mapping->pegawai_id,
                ],
            ]
        );

        return ApiFormatter::success($mapping, 'Shift berhasil ditolak');
    }

    public function requestUpdate(Request $request, $id)
    {
        $user = auth()->user();

        $shift = ShiftMapping::where('id', $id)
            ->where('pegawai_id', $user->id)
            ->first();

        if (!$shift) {
            return ApiFormatter::error('Data shift tidak ditemukan atau bukan milik Anda', 403);
        }

        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'toleransi_telat' => 'nullable|integer|min:0',
        ]);

        // SIMPAN SHIFT LAMA
        $shift->shift_lama_id = $shift->shift_id;

        // SET SHIFT BARU (BELUM AKTIF)
        $shift->shift_id = $validated['shift_id'];
        $shift->tanggal_mulai = $validated['tanggal_mulai'];
        $shift->tanggal_selesai = $validated['tanggal_selesai'] ?? null;
        $shift->status = 'pending';
        $shift->requested_by = $user->id;  
        $shift->approved_by = null;       
        $shift->approved_at = null;
        $shift->save();

        // ✅ FIX: Ubah type menjadi request_shift_submitted (konsisten dengan frontend)
        NotificationService::create(
            [
                'type' => 'request_shift_submitted', // ✅ CHANGED
                'title' => 'Pengajuan Request Shift',
                'message' => "Pegawai {$user->name} mengajukan request shift",
                'company_id' => $shift->company_id,
                'data' => [
                    'shift_mapping_id' => $shift->id,
                    'pegawai_id' => $user->id,
                    'pegawai_nama' => $user->name, // ✅ ADDED
                ]
            ],
            [
                [
                    'role' => 'admin',
                    'company_id' => $shift->company_id,
                ],
                [
                    'role' => 'superadmin',
                ],
            ]
        );

        return ApiFormatter::success($shift, 'Request perubahan shift berhasil dibuat');
    }

    public function today($pegawai_id)
    {
        $today = date('Y-m-d');

        $shift = ShiftMapping::with('shift')
            ->where('pegawai_id', $pegawai_id)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('tanggal_selesai')
                ->orWhereDate('tanggal_selesai', '>=', $today);
            })
            ->first();

        return ApiFormatter::success($shift, 'Shift hari ini');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->dashboard_type === 'pegawai') {
            return ApiFormatter::error('Unauthorized', 403);
        }

        // Ambil data mapping
        $mapping = ShiftMapping::find($id);

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

    private function shiftMasihAktifQuery($query, $today)
    {
        return $query->where(function ($q) use ($today) {

            // Shift hari ini & ke depan
            $q->where('tanggal_mulai', '>=', $today)

            // Shift kemarin tapi absensi belum pulang
            ->orWhereExists(function ($sub) {
                $sub->selectRaw(1)
                    ->from('absensis')
                    ->whereColumn('absensis.pegawai_id', 'shift_mapping.pegawai_id')
                    ->whereColumn('absensis.tanggal', 'shift_mapping.tanggal_mulai')
                    ->whereNull('absensis.jam_pulang');
            });
        });
    }

    public function byDate($pegawaiId, Request $request)
    {
        $tanggal = $request->tanggal;

        $shift = ShiftMapping::with('shift')
            ->where('pegawai_id', $pegawaiId)
            ->where('tanggal_mulai', $tanggal)
            ->first();

        return ApiFormatter::success($shift);
    }

    public function public()
    {
        return response()->json([
            'data' => Shift::select('id','nama','jam_masuk','jam_pulang')->get()
        ]);
    }



}