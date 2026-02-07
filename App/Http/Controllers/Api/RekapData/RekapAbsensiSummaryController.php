<?php

namespace App\Http\Controllers\Api\RekapData;

use App\Exports\RekapAbsensiExport;
use App\Exports\RekapAbsensiSummaryExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Helpers\ApiFormatter;
use App\Models\Company;
use App\Models\Lembur;
use App\Models\Pegawai;
use App\Models\Payroll;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cuti;

class RekapAbsensiSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $pegawaiQuery = Pegawai::query();


        // ================= VALIDATION =================
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'pegawai_id' => 'nullable|exists:pegawais,id',
        ]);


        // ================= BASE QUERY =================
        $query = Absensi::with('pegawai:id,name,company_id');

        // ================= SUPERADMIN =================
        if ($user->dashboard_type === 'superadmin' && $request->filled('company_id')) {
            $pegawaiQuery->where('company_id', $request->company_id);
        }

        // ================= ADMIN =================
        if ($user->dashboard_type === 'admin') {
            $pegawaiQuery->where('company_id', $user->company_id);
        }

        // ================= FILTER TANGGAL =================
        $query->whereBetween('tanggal', [
            Carbon::parse($request->start_date)->startOfDay(),
            Carbon::parse($request->end_date)->endOfDay(),
        ]);

        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        // ================= GROUP BY PEGAWAI =================
        $pegawais = $pegawaiQuery->get(['id', 'name', 'tgl_join']);
        $absensi = $query->get()->groupBy('pegawai_id');

        // CEK PAYROLL YANG SUDAH ADA UNTUK PERIODE INI
        $periodeString = $request->start_date . ' - ' . $request->end_date;

        $existingPayrolls = Payroll::whereIn('pegawai_id', $pegawais->pluck('id'))
            ->where('periode', $request->start_date . ' - ' . $request->end_date)
            ->pluck('pegawai_id')
            ->toArray();




        // ================= PROCESS DATA =================
        $result = $pegawais->map(function ($pegawai) use ($absensi, $request, $existingPayrolls) {
            $items = $absensi->get($pegawai->id, collect()); 
            $start = Carbon::parse($request->start_date);
            $end   = Carbon::parse($request->end_date);

            // POTONG DARI TANGGAL JOIN
            $joinDate = Carbon::parse($pegawai->tgl_join);
            if ($joinDate->gt($start)) {
                $start = $joinDate;
            }


            // TOTAL HARI DALAM RANGE
            $totalHariRange = $start->diffInDays($end) + 1;
            // TOTAL HADIR
            $totalHadir = $items->where('status', 'hadir')->count();
            // TOTAL LIBUR
            $totalLibur = $items->where('status', 'libur')->count();

            $cutiSummary = [
                'cuti' => 0,
                'sakit' => 0,
                'izin_masuk' => 0,
                'izin_telat' => 0,
                'izin_pulang_cepat' => 0,
            ];

            $totalHariKerja = max(
                $totalHariRange
                - $totalLibur
                - (
                    $cutiSummary['cuti']
                + $cutiSummary['sakit']
                + $cutiSummary['izin_masuk']
                ),
                0
            );

            $totalAlfa = max($totalHariKerja - $totalHadir, 0);

            $persentaseKehadiran = $totalHariKerja > 0
                ? round(($totalHadir / $totalHariKerja) * 100, 2)
                : 0;

            // CEK APAKAH PAYROLL SUDAH ADA
            $hasPayroll = in_array($pegawai->id, $existingPayrolls);

            // ================= TELAT =================
            $totalTelatMenit = $items->sum('telat'); // total menit telat
            $totalTelatCount = $items->where('telat', '>', 0)->count(); // total kejadian telat

            $telatJam = intdiv($totalTelatMenit, 60);
            $telatMenit = $totalTelatMenit % 60;

            if ($totalTelatMenit > 0) {
                $totalTelatFormatted =
                    "{$totalTelatMenit} Menit\n{$totalTelatCount} x telat";
            } else {
                $totalTelatFormatted = "Tidak Pernah Telat";
            }

            // ================= PULANG CEPAT =================
            $totalPulangCepatMenit = $items->sum('pulang_cepat');
            $totalPulangCepatCount = $items->where('pulang_cepat', '>', 0)->count();

            $pulangCepatJam = intdiv($totalPulangCepatMenit, 60);
            $pulangCepatMenit = $totalPulangCepatMenit % 60;

            if ($totalPulangCepatMenit > 0) {
                $totalPulangCepatFormatted =
                    "{$totalPulangCepatMenit} Menit\n{$totalPulangCepatCount} x pulang cepat";
            } else {
                $totalPulangCepatFormatted = "Tidak Pernah Pulang Cepat";
            }

            // ================= LEMBUR =================
            $totalLemburMenit = Lembur::where('pegawai_id', $pegawai->id)
                ->where('status', 'disetujui')
                ->whereBetween('tanggal_lembur', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay(),
                ])
                ->sum('total_lembur_menit'); // ⬅️ SUDAH MENIT

            $totalLemburCount = Lembur::where('pegawai_id', $pegawai->id)
                ->where('status', 'disetujui')
                ->whereBetween('tanggal_lembur', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay(),
                ])
                ->count();

            // konversi menit ke jam & menit
            $lemburJam = intdiv($totalLemburMenit, 60);
            $lemburMenit = $totalLemburMenit % 60;

            if ($totalLemburMenit > 0) {
                $totalLemburFormatted =
                    "{$totalLemburMenit} Menit\n{$totalLemburCount} x lembur";
            } else {
                $totalLemburFormatted = "Tidak Pernah Lembur";
            }


            $cutiSummary = [
                'cuti' => 0,
                'sakit' => 0,
                'izin_masuk' => 0,
                'izin_telat' => 0,
                'izin_pulang_cepat' => 0,
            ];



            // ================= CUTI (DARI TABEL CUTIS) =================
            $cutis = Cuti::where('pegawai_id', $pegawai->id)
                ->where('status', 'disetujui')
                ->where(function ($q) use ($request) {
                    $q->whereBetween('tanggal_mulai', [
                        $request->start_date,
                        $request->end_date
                    ])
                    ->orWhereBetween('tanggal_selesai', [
                        $request->start_date,
                        $request->end_date
                    ])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('tanggal_mulai', '<=', $request->start_date)
                        ->where('tanggal_selesai', '>=', $request->end_date);
                    });
                })
                ->get();



            $totalCutiHari = 0;

            foreach ($cutis as $c) {
                $mulai = Carbon::parse($c->tanggal_mulai);
                $selesai = $c->tanggal_selesai
                    ? Carbon::parse($c->tanggal_selesai)
                    : $mulai;

                // 🔒 POTONG SESUAI RANGE REKAP
                $start = Carbon::parse($request->start_date);
                $end   = Carbon::parse($request->end_date);

                if ($mulai->lt($start)) $mulai = $start;
                if ($selesai->gt($end)) $selesai = $end;

                //$totalHari = $mulai->diffInDays($selesai) + 1;
                // 🎯 KLASIFIKASI
                //if (isset($cutiSummary[$c->jenis_cuti])) {
                //    $cutiSummary[$c->jenis_cuti] += $totalHari;
                //}

                $hariCuti = $mulai->diffInDays($selesai) + 1;

                if (isset($cutiSummary[$c->jenis_cuti])) {
                    $cutiSummary[$c->jenis_cuti] += $hariCuti;
                }

            }

            $totalCutiIzin =
                    $cutiSummary['cuti']
                + $cutiSummary['sakit']
                + $cutiSummary['izin_masuk'];

            // HITUNG HARI KERJA
            $totalHariKerja = max(
                $totalHariRange
                - $totalLibur
                - (
                    $cutiSummary['cuti']
                + $cutiSummary['sakit']
                + $cutiSummary['izin_masuk']
                ),
                0
            );

            $persentaseKehadiran = $totalHariKerja > 0
                ? round(($totalHadir / $totalHariKerja) * 100, 2)
                : 0;


            // ================= RETURN =================
            return [
                'pegawai_id' => $pegawai->id,
                'nama_pegawai' => $pegawai->name,
                'has_payroll' => $hasPayroll,

                // ===== IZIN & STATUS =====
                'total_cuti' => $cutiSummary['cuti'],
                'total_izin_masuk' => $cutiSummary['izin_masuk'],
                'total_sakit' => $cutiSummary['sakit'],
                'total_izin_telat' => $cutiSummary['izin_telat'],
                'total_izin_pulang_cepat' => $cutiSummary['izin_pulang_cepat'],

                // ===== KEHADIRAN =====
                'total_hadir' => $totalHadir,
                'total_alfa' => $totalAlfa,
                'total_libur' => $totalLibur,

                // ===== TELAT =====
                'total_telat_menit' => $totalTelatMenit,
                'total_telat_count' => $totalTelatCount,
                'total_telat' => $totalTelatFormatted,

                // ===== PULANG CEPAT =====
                'total_pulang_cepat_menit' => $totalPulangCepatMenit,
                'total_pulang_cepat_count' => $totalPulangCepatCount,
                'total_pulang_cepat' => $totalPulangCepatFormatted,

                // ===== LEMBUR =====
                'total_lembur_menit' => $totalLemburMenit,
                'total_lembur_count' => $totalLemburCount,
                'total_lembur' => $totalLemburFormatted,

                'persentase_kehadiran' => $persentaseKehadiran,

            ];
        })->values();

        return ApiFormatter::success($result, 'Rekap absensi per pegawai');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
            'pegawai_id' => 'nullable|exists:pegawais,id',
        ]);

        return Excel::download(
            new RekapAbsensiExport(
                $request->start_date,
                $request->end_date,
                $request->company_id,
                $request->pegawai_id
            ),
            'rekap-absensi-detail.xlsx'
        );
    }

    /* ===================== PDF ===================== */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
            'pegawai_id' => 'nullable|exists:pegawais,id',
        ]);

        // ===== COMPANY =====
        $company = Company::find($request->company_id);

        // ===== AMBIL DATA DARI EXPORT =====
        $export = new RekapAbsensiExport(
            $request->start_date,
            $request->end_date,
            $request->company_id,
            $request->pegawai_id
        );

        $rows = $export->collection();

        // ===== GENERATE PDF =====
        $pdf = Pdf::loadView('pdf.rekap-absensi', [
            'rows'    => $rows,
            'company' => $company,
            'start'   => $request->start_date,
            'end'     => $request->end_date,
        ])
        ->setPaper('A4', 'landscape');

        return $pdf->download('rekap-absensi-detail.pdf');
    }

    public function exportRekap(Request $request)
    {
        // PAKAI LOGIC SUMMARY YANG SUDAH ADA
        $request->merge([
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        // Ambil hasil summary dari method index
        $response = $this->index($request);

        if ($user->dashboard_type === 'admin') {
            $request->merge([
                'company_id' => $user->company_id
            ]);
        }

        $data = collect($response->getData()->data);

        return Excel::download(
            new RekapAbsensiSummaryExport($data),
            'rekap-absensi-summary.xlsx'
        );
    }

    public function exportRekapPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        // ======================
        // AMBIL DATA SUMMARY
        // ======================
        $response = $this->index($request);
        $rows = collect($response->getData()->data);

        // ======================
        // COMPANY (UNTUK HEADER)
        // ======================
        $user = auth()->user();
        $company = null;

        if ($user->dashboard_type === 'superadmin' && $request->filled('company_id')) {
            $company = Company::find($request->company_id);
        }

        if ($user->dashboard_type === 'admin') {
            $company = Company::find($user->company_id);
        }


        // ======================
        // GENERATE PDF
        // ======================
        $pdf = Pdf::loadView('pdf.rekap-absensi-summary', [
            'rows'    => $rows,
            'company' => $company,
            'start'   => $request->start_date,
            'end'     => $request->end_date,
        ])
        ->setPaper('A4', 'landscape');

        return $pdf->download('rekap-absensi-summary.pdf');
    }


    protected $pegawaiId;

    public function totalKasbonApprove($pegawaiId)
    {
        $total = \DB::table('kasbons')
            ->where('pegawai_id', $pegawaiId)
            ->where('status', 'approve')
            ->sum('nominal');

        return response()->json([
            'total' => (float) $total
        ]);
    }


}
