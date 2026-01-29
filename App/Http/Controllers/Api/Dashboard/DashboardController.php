<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pegawai;
use App\Models\Lembur;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function metrics(Request $request)
    {
        $user = $request->user();

        $now = Carbon::now();
        $awalBulan = $now->copy()->startOfMonth();
        $akhirBulan = $now->copy()->endOfMonth();

        /** =========================
         * TOTAL PEGAWAI
         * ========================= */
        $pegawaiQuery = Pegawai::where('status', 'active');

        if ($user->dashboard_type === 'admin') {
            $pegawaiQuery->where('company_id', $user->company_id);
        }

        $totalPegawai = $pegawaiQuery->count();

        /** =========================
         * LEMBUR BULAN INI
         * ========================= */
        $lemburQuery = Lembur::where('status', 'disetujui')
            ->whereBetween('tanggal_lembur', [$awalBulan, $akhirBulan]);

        if ($user->dashboard_type === 'admin') {
            $lemburQuery->where('company_id', $user->company_id);
        }

        $totalLembur = $lemburQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * CUTI BULAN INI
         * ========================= */
        $cutiQuery = Cuti::where('status', 'disetujui')
            ->where('jenis_cuti', 'cuti')
            ->where(function ($q) use ($awalBulan, $akhirBulan) {
                $q->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                  ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan])
                  ->orWhere(function ($q2) use ($awalBulan, $akhirBulan) {
                      $q2->where('tanggal_mulai', '<=', $awalBulan)
                         ->where('tanggal_selesai', '>=', $akhirBulan);
                  });
            });

        if ($user->dashboard_type === 'admin') {
            $cutiQuery->where('company_id', $user->company_id);
        }

        $totalCuti = $cutiQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * SAKIT BULAN INI
         * ========================= */
        $sakitQuery = Cuti::where('status', 'disetujui')
            ->where('jenis_cuti', 'sakit')
            ->where(function ($q) use ($awalBulan, $akhirBulan) {
                $q->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan])
                ->orWhere(function ($q2) use ($awalBulan, $akhirBulan) {
                    $q2->where('tanggal_mulai', '<=', $awalBulan)
                        ->where('tanggal_selesai', '>=', $akhirBulan);
                });
            });

        if ($user->dashboard_type === 'admin') {
            $sakitQuery->where('company_id', $user->company_id);
        }

        $totalSakit = $sakitQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * Izin Masuk
         * ========================= */
        $izinQuery = Cuti::where('status', 'disetujui')
            ->where('jenis_cuti', 'izin_masuk')
            ->where(function ($q) use ($awalBulan, $akhirBulan) {
                $q->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan])
                ->orWhere(function ($q2) use ($awalBulan, $akhirBulan) {
                    $q2->where('tanggal_mulai', '<=', $awalBulan)
                        ->where('tanggal_selesai', '>=', $akhirBulan);
                });
            });

        if ($user->dashboard_type === 'admin') {
            $izinQuery->where('company_id', $user->company_id);
        }

        $totalIzin = $izinQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * Izin Telat
         * ========================= */
        $telatQuery = Cuti::where('status', 'disetujui')
            ->where('jenis_cuti', 'izin_telat')
            ->where(function ($q) use ($awalBulan, $akhirBulan) {
                $q->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan])
                ->orWhere(function ($q2) use ($awalBulan, $akhirBulan) {
                    $q2->where('tanggal_mulai', '<=', $awalBulan)
                        ->where('tanggal_selesai', '>=', $akhirBulan);
                });
            });

        if ($user->dashboard_type === 'admin') {
            $telatQuery->where('company_id', $user->company_id);
        }

        $totalTelat = $telatQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * Izin Pulan Cepat
         * ========================= */
        $pulangQuery = Cuti::where('status', 'disetujui')
            ->where('jenis_cuti', 'izin_pulang_cepat')
            ->where(function ($q) use ($awalBulan, $akhirBulan) {
                $q->whereBetween('tanggal_mulai', [$awalBulan, $akhirBulan])
                ->orWhereBetween('tanggal_selesai', [$awalBulan, $akhirBulan])
                ->orWhere(function ($q2) use ($awalBulan, $akhirBulan) {
                    $q2->where('tanggal_mulai', '<=', $awalBulan)
                        ->where('tanggal_selesai', '>=', $akhirBulan);
                });
            });

        if ($user->dashboard_type === 'admin') {
            $pulangQuery->where('company_id', $user->company_id);
        }

        $totalPulang = $pulangQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * HADIR BULAN INI
         * ========================= */
        $hadirQuery = Absensi::where('status', 'hadir')
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan]);

        if ($user->dashboard_type === 'admin') {
            $hadirQuery->where('company_id', $user->company_id);
        }

        $totalHadir = $hadirQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * Alfa BULAN INI
         * ========================= */
        $alfaQuery = Absensi::where('status', 'alpha')
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan]);

        if ($user->dashboard_type === 'admin') {
            $alfaQuery->where('company_id', $user->company_id);
        }

        $totalAlfa = $alfaQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * Libur BULAN INI
         * ========================= */
        $liburQuery = Absensi::where('status', 'libur')
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan]);

        if ($user->dashboard_type === 'admin') {
            $liburQuery->where('company_id', $user->company_id);
        }

        $totalLibur = $liburQuery
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        /** =========================
         * Total Kasbon
         * ========================= */
        $now = Carbon::now();

        $totalKasbonBulanIni = DB::table('kasbons')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->where('status', 'approve')
            ->sum('nominal');

        /** =========================
         * Total Reimbursement
         * ========================= */

        $totalReimbursementBulanIni = DB::table('reimbursements')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->where('status', 'approve')
            ->sum('jumlah');

        return response()->json([
            'total_pegawai'     => $totalPegawai,
            'hadir_bulan_ini'   => $totalHadir,
            'alfa_bulan_ini'   => $totalAlfa,
            'libur_bulan_ini'   => $totalLibur,
            'lembur_bulan_ini'  => $totalLembur,
            'kasbon_bulan_ini' => $totalKasbonBulanIni,
            'reimbursement_bulan_ini' => $totalReimbursementBulanIni,
            'cuti_bulan_ini'    => $totalCuti,
            'sakit_bulan_ini'   => $totalSakit,
            'izin_bulan_ini'   => $totalIzin,
            'telat_bulan_ini'   => $totalTelat,
            'pulang_bulan_ini'   => $totalPulang,
            'bulan'             => $now->translatedFormat('F Y'),
        ]);
    }
}
