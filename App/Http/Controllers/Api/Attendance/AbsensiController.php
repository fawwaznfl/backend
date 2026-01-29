<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Shift;
use App\Http\Requests\StoreAbsensiRequest;
use App\Http\Requests\UpdateAbsensiRequest;
use App\Helpers\ApiFormatter;
use App\Models\Cuti;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMapping;


class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Absensi::with([
            'pegawai:id,name,company_id',
            'shift:id,nama'
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
            ->orderBy('tanggal', 'desc')
            ->get();

        return ApiFormatter::success($data, 'Absensi fetched');
    }

    public function store(StoreAbsensiRequest $request)
    {
        $user = auth()->user();
        $payload = $request->validated();

        // Inject data otomatis
        $payload['company_id'] = $user->pegawai->company_id ?? null;
        $payload['created_by'] = $user->id;

        $absensi = Absensi::create($payload);

        return ApiFormatter::success($absensi, 'Absensi created', 201);
    }

    public function show($id)
    {
        $absensi = Absensi::with(['pegawai', 'shift', 'lokasi'])->find($id);

        if (!$absensi) {
            return ApiFormatter::error('Absensi not found', 404);
        }

        return ApiFormatter::success($absensi, 'Absensi found');
    }

    public function update(UpdateAbsensiRequest $request, $id)
    {
        $absensi = Absensi::find($id);
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
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return ApiFormatter::error('Absensi not found', 404);
        }

        $absensi->delete();

        return ApiFormatter::success(null, 'Absensi deleted', 204);
    }

    public function self()
    {
        $pegawaiId = auth()->user()->pegawai->id;

        $data = Absensi::where('pegawai_id', $pegawaiId)
            ->orderBy('tanggal', 'desc')
            ->get();

        return ApiFormatter::success($data, 'Absensi self fetched');
    }

    public function absenMasuk(Request $request)
    {
        $pegawai = auth()->user();
        $now = now()->format('H:i:s');

        // Ambil shift hari ini
        $shift = Shift::find($request->shift_id);

        // Hitung telat
        $shiftStart = Carbon::createFromFormat('H:i:s', $shift->jam_masuk);
        $actualStart = Carbon::createFromFormat('H:i:s', $now);

        $telat = $actualStart->greaterThan($shiftStart)
            ? $shiftStart->diff($actualStart)->format('%H:%I:%S')
            : '00:00:00';

        // Simpan absen
        $absen = Absensi::create([
            'pegawai_id'   => $pegawai->id,
            'company_id'   => $pegawai->company_id,
            'lokasi_id'    => $pegawai->lokasi_id,
            'shift_id'     => $shift->id,
            'tanggal'      => now()->toDateString(),
            'jam_masuk'    => $now,
            'lokasi_masuk' => $request->lokasi_masuk,
            'foto_masuk'   => $request->foto_masuk,
            'telat'        => $telat,
        ]);

        return ApiFormatter::success($absen, 'Absen masuk berhasil');
    }


    public function absenPulang(Request $request)
    {
        $pegawai = auth()->user()->pegawai;
        $today = now()->toDateString();

        $absensi = Absensi::where('pegawai_id', $pegawai->id)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi) {
            return ApiFormatter::error('Belum absen masuk hari ini', 400);
        }

        if ($absensi->jam_pulang) {
            return ApiFormatter::error('Sudah absen pulang hari ini', 400);
        }

        $absensi->update([
            'jam_pulang' => now()->format('H:i'),
            'lokasi_pulang' => $request->lokasi ?? null,
        ]);

        return ApiFormatter::success($absensi, 'Absen pulang berhasil');
    }

    public function checkToday($pegawai_id)
    {
        $today = date('Y-m-d');

        $absen = Absensi::where('pegawai_id', $pegawai_id)
            ->where('tanggal', $today)
            ->first();

        return ApiFormatter::success($absen, 'Status absensi hari ini');
    }

    public function autoAbsen(Request $request)
    {
        // =========================
        // 1. VALIDASI
        // =========================
        $validated = $request->validate([
            'pegawai_id' => 'required',
            'shift_id'   => 'required',
            'tanggal'    => 'required|date',
            'lokasi'     => 'required',
            'foto'       => 'required|file|mimes:jpg,jpeg,png',
        ]);

        $pegawaiId   = $validated['pegawai_id'];
        $tanggal     = $validated['tanggal']; // ⬅️ PAKAI TANGGAL DARI REQUEST
        $user        = auth()->user();
        $jamSekarang = now()->format('H:i:s');

        // =========================
        // 2. CEK CUTI (SESUI TANGGAL YANG DIABSEN)
        // =========================
        $cuti = Cuti::where('pegawai_id', $pegawaiId)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->where('status', 'disetujui')
            ->first();

        if ($cuti) {
            return ApiFormatter::error(
                "Anda sedang {$cuti->jenis_cuti} pada tanggal {$tanggal}",
                403
            );
        }

        // =========================
        // 3. PRIORITAS: ABSEN BELUM PULANG (LINTAS HARI)
        // =========================
        $absenBelumPulang = Absensi::where('pegawai_id', $pegawaiId)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->where('status', '!=', 'alpha')
            ->orderBy('tanggal', 'asc')
            ->first();


        if ($absenBelumPulang) {

            $shift = Shift::find($absenBelumPulang->shift_id);
            $fotoPulang = $request->file('foto')->store('absensi', 'public');

            // HITUNG PULANG CEPAT
            $pulangCepat = 0;
            if ($shift && $jamSekarang < $shift->jam_pulang) {
                $pulangCepat = abs(
                    now()->setTimeFromTimeString($shift->jam_pulang)
                        ->diffInMinutes(now(), false)
                );
            }

            $absenBelumPulang->update([
                'jam_pulang'    => $jamSekarang,
                'lokasi_pulang' => $request->lokasi,
                'foto_pulang'   => $fotoPulang,
                'pulang_cepat'  => $pulangCepat,
                'status'        => 'hadir',
            ]);

            return ApiFormatter::success(
                "Absen pulang berhasil untuk tanggal {$absenBelumPulang->tanggal}"
            );
        }

        // =========================
        // 4. CEK ABSEN MASUK (TANGGAL YANG DIKLIK)
        // =========================
        $absenTanggal = Absensi::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $tanggal)
            ->first();

        if ($absenTanggal) {
            return ApiFormatter::error(
                "Absensi tanggal {$tanggal} sudah ada",
                400
            );
        }

        // =========================
        // 5. ABSEN MASUK
        // =========================
        $shift = Shift::find($validated['shift_id']);
        $fotoMasuk = $request->file('foto')->store('absensi', 'public');

        $telat = 0;
        $toleransi = 0;

        $shiftMapping = ShiftMapping::where('pegawai_id', $pegawaiId)
            ->where('tanggal_mulai', $tanggal)
            ->first();

        if ($shiftMapping) {
            $toleransi = (int) $shiftMapping->toleransi_telat;
        }

        if ($shift) {
            $jamShift = Carbon::createFromFormat('H:i:s', $shift->jam_masuk);
            $jamMasuk = Carbon::createFromFormat('H:i:s', $jamSekarang);

            // hanya hitung jika benar-benar telat
            if ($jamMasuk->greaterThan($jamShift)) {

                // ⬅️ INI YANG BENAR
                $selisihMenit = $jamShift->diffInMinutes($jamMasuk);

                $telat = max(0, $selisihMenit - $toleransi);
            }
        }

        // =========================
        // HITUNG STATUS TOLERANSI
        // =========================
        $shiftMapping = ShiftMapping::where('pegawai_id', $pegawaiId)
            ->where('tanggal_mulai', $tanggal)
            ->first();

        $statusToleransi = 'tepat_waktu';

        if ($shiftMapping && $shift) {

            $jamShift = Carbon::createFromFormat('H:i:s', $shift->jam_masuk);
            $jamMasuk = Carbon::createFromFormat('H:i:s', $jamSekarang);

            $selisihMenit = $jamMasuk->diffInMinutes($jamShift, false);

            if ($selisihMenit > 0) {
                if ($selisihMenit <= $shiftMapping->toleransi_telat) {
                    $statusToleransi = "toleransi {$selisihMenit} menit";
                } else {
                    $statusToleransi = 'terlambat';
                }
            }

            $shiftMapping->update([
                'status_toleransi' => $statusToleransi
            ]);
        }



        Absensi::create([
            'pegawai_id'   => $pegawaiId,
            'company_id'   => $user->company_id,
            'lokasi_id'    => $user->lokasi_id,
            'shift_id'     => $validated['shift_id'],
            'tanggal'      => $tanggal,
            'jam_masuk'    => $jamSekarang,
            'lokasi_masuk' => $request->lokasi,
            'foto_masuk'   => $fotoMasuk,
            'telat'        => $telat,
            'status'       => 'hadir',
        ]);

        return ApiFormatter::success(
            [
                'tanggal' => $tanggal,
                'telat_menit' => $telat,
                'status_toleransi' => $statusToleransi
            ],
            "Absen masuk berhasil"
        );

    }



    public function statusPegawai($pegawai_id) //
    {
        $today = date('Y-m-d');

        $absen = Absensi::where('pegawai_id', $pegawai_id)
            ->where('tanggal', $today)
            ->first();

        if (!$absen) {
            return ApiFormatter::success([
                "status_absen" => "belum_masuk"
            ]);
        }

        if ($absen->jam_masuk && !$absen->jam_pulang) {
            return ApiFormatter::success([
                "status_absen" => "sudah_masuk"
            ]);
        }

        if ($absen->jam_masuk && $absen->jam_pulang) {
            return ApiFormatter::success([
                "status_absen" => "sudah_pulang"
            ]);
        }
    }

    public function byPegawai($pegawaiId)
    {
        $data = Absensi::where('pegawai_id', $pegawaiId)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($a) {
                if (!$a->jam_masuk) {
                    $status = 'belum_masuk';
                } elseif ($a->jam_masuk && !$a->jam_pulang) {
                    $status = 'sudah_masuk';
                } else {
                    $status = 'sudah_pulang';
                }

                return [
                    'id'           => $a->id,
                    'tanggal'      => $a->tanggal,
                    'jam_masuk'    => $a->jam_masuk,
                    'jam_pulang'   => $a->jam_pulang,
                    'status_absen' => $status,
                    'status'       => $a->status ?? 'alpha',
                ];
            });

        return ApiFormatter::success($data, 'Data absensi');
    }

    public function absensiAktif($pegawaiId)
    {
        // Cari absensi yang belum pulang (lintas hari)
        $absen = Absensi::where('pegawai_id', $pegawaiId)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->where('status', '!=', 'alpha')

            ->orderBy('tanggal', 'asc')
            ->first();

        if (!$absen) {
            return ApiFormatter::success(null, 'Tidak ada absensi aktif');
        }

        return ApiFormatter::success([
            'tanggal' => $absen->tanggal,
            'shift_id' => $absen->shift_id,
            'status' => 'belum_pulang'
        ], 'Masih ada absensi aktif');
    }

}
