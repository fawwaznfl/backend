<?php

namespace App\Http\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Shift;
use App\Models\Pegawai;
use App\Http\Requests\StoreAbsensiRequest;
use App\Http\Requests\UpdateAbsensiRequest;
use App\Helpers\ApiFormatter;
use App\Http\Requests\StoreDinasLuarRequest;
use App\Http\Requests\UpdateDinasLuarRequest;
use App\Models\DinasLuar;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;

class DinasLuarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = DinasLuar::with([
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

        return ApiFormatter::success($data, 'AbsensiDinasLuar fetched');
    }

    public function store(StoreDinasLuarRequest $request)
    {
        $user = auth()->user();
        $payload = $request->validated();

        // Inject data otomatis
        $payload['company_id'] = $user->pegawai->company_id ?? null;
        $payload['created_by'] = $user->id;

        $absensidinasluar = DinasLuar::create($payload);

        return ApiFormatter::success($absensidinasluar, 'AbsensiDinasLuar created', 201);
    }

    public function show($id)
    {
        $absensidinasluar = DinasLuar::with(['pegawai', 'shift', 'lokasi'])->find($id);

        if (!$absensidinasluar) {
            return ApiFormatter::error('AbsensiDinasLuar not found', 404);
        }

        return ApiFormatter::success($absensidinasluar, 'Absensi found');
    }

    public function update(UpdateDinasLuarRequest $request, $id)
    {
        $absensidinasluar = DinasLuar::find($id);
        if (!$absensidinasluar) {
            return ApiFormatter::error('AbsensiDinasLuar not found', 404);
        }

        $payload = $request->validated();
        $payload['updated_by'] = auth()->id();

        $absensidinasluar->update($payload);

        return ApiFormatter::success($absensidinasluar, 'Absensi updated');
    }

    public function destroy($id)
    {
        $absensidinasluar = DinasLuar::find($id);
        if (!$absensidinasluar) {
            return ApiFormatter::error('AbsensiDinasLuar not found', 404);
        }

        $absensidinasluar->delete();

        return ApiFormatter::success(null, 'AbsensiDinasLuar deleted', 204);
    }

    public function self()
    {
        $pegawaiId = auth()->user()->pegawai->id;

        $data = DinasLuar::where('pegawai_id', $pegawaiId)
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
        $absen = DinasLuar::create([
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

        $absensidinasluar = DinasLuar::where('pegawai_id', $pegawai->id)
            ->where('tanggal', $today)
            ->first();

        if (!$absensidinasluar) {
            return ApiFormatter::error('Belum absen masuk hari ini', 400);
        }

        if ($absensidinasluar->jam_pulang) {
            return ApiFormatter::error('Sudah absen pulang hari ini', 400);
        }

        $absensidinasluar->update([
            'jam_pulang' => now()->format('H:i'),
            'lokasi_pulang' => $request->lokasi ?? null,
        ]);

        return ApiFormatter::success($absensidinasluar, 'Absen pulang berhasil');
    }

    public function checkToday($pegawai_id)
    {
        $today = date('Y-m-d');

        $absen = DinasLuar::where('pegawai_id', $pegawai_id)
            ->where('tanggal', $today)
            ->first();

        return ApiFormatter::success($absen, 'Status AbsensiDinasLuar hari ini');
    }

    public function autoAbsen(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'shift_id' => 'required',
            'tanggal' => 'required|date',
            'lokasi' => 'required',
            'foto' => 'required|file|mimes:jpg,jpeg,png',
        ]);

        $user = auth()->user(); 
        
        $pegawaiId = $request->pegawai_id;

        // Ambil shift detail untuk hitung telat / pulang cepat
        $shift = Shift::find($request->shift_id);

        // Cek apakah sudah absen masuk
        $absen = DinasLuar::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $request->tanggal)
            ->first();

        // ABSEN MASUK
        if (!$absen) {

            $fotoMasuk = $request->file('foto')->store('absensi', 'public');
            $jamSekarang = now()->format('H:i:s');

            // HITUNG TELAT
            $telat = 0;
            if ($shift && $jamSekarang > $shift->jam_masuk) {
                $telat = now()->diffInMinutes(
                    now()->setTimeFromTimeString($shift->jam_masuk),
                    false
                );
                $telat = abs($telat);
            }

            DinasLuar::create([
                'pegawai_id'      => $pegawaiId,
                'company_id'      => $user->company_id,
                'lokasi_id'       => $user->lokasi_id,
                'shift_id'        => $request->shift_id,
                'tanggal'         => $request->tanggal,
                'jam_masuk'       => $jamSekarang,
                'lokasi_masuk'    => $request->lokasi,
                'foto_masuk'      => $fotoMasuk,
                'telat'           => $telat,
            ]);

            return ApiFormatter::success("Absen masuk berhasil (Telat: {$telat} menit)");
        }

        // ABSEN PULANG
        if (!$absen->jam_pulang) {

            $fotoPulang = $request->file('foto')->store('absensi', 'public');
            $jamSekarang = now()->format('H:i:s');

            // HITUNG PULANG CEPAT
            $pulangCepat = 0;
            if ($shift && $jamSekarang < $shift->jam_pulang) {
                $pulangCepat = now()->setTimeFromTimeString($shift->jam_pulang)
                    ->diffInMinutes(now(), false);
                $pulangCepat = abs($pulangCepat);
            }

            $absen->update([
                'jam_pulang'     => $jamSekarang,
                'lokasi_pulang'  => $request->lokasi,
                'foto_pulang'    => $fotoPulang,
                'pulang_cepat'   => $pulangCepat,
            ]);

            return ApiFormatter::success("Absen pulang berhasil (Pulang cepat: {$pulangCepat} menit)");
        }

        return ApiFormatter::error("Anda sudah absen masuk & pulang hari ini", 400);
    }

    public function statusPegawai($pegawai_id)
    {
        $today = date('Y-m-d');

        $absen = DinasLuar::where('pegawai_id', $pegawai_id)
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
        $data = DinasLuar::where('pegawai_id', $pegawaiId)
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
                ];
            });

        return ApiFormatter::success($data, 'Data absensi');
    }

}
