<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Payroll::with(['pegawai.divisi']);

        // 🔥 SUPERADMIN → lihat semua
        if ($user->dashboard_type === 'superadmin') {
            // no filter
        }

        // 🔥 ADMIN → hanya company sendiri
        elseif ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        // 🔥 PEGAWAI → hanya data dirinya
        elseif ($user->dashboard_type === 'pegawai') {
            $query->where('pegawai_id', $user->id);
        }


        return response()->json(
            $query->orderByDesc('created_at')->get()
        );
    }

    // 🔹 Create new payroll
    public function store(Request $request)
    {
        $request->validate([
            'kehadiran_100' => 'nullable|integer|min:0',
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',

            'gaji_pokok' => 'required|numeric|min:0',
            'uang_transport' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'uang_lembur' => 'nullable|numeric|min:0',

            'bonus_kehadiran' => 'nullable|numeric|min:0',
            'bonus_pribadi' => 'nullable|numeric|min:0',
            'bonus_team' => 'nullable|numeric|min:0',
            'bonus_jackpot' => 'nullable|numeric|min:0',

            'thr' => 'nullable|integer|min:0',
            'tunjangan_hari_raya' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
            'tunjangan_bpjs_kesehatan' => 'nullable|numeric|min:0',
            'tunjangan_bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'tunjangan_pajak' => 'nullable|numeric|min:0',

            'total_tambah' => 'required|numeric|min:0',

            'terlambat' => 'nullable|integer|min:0',
            'uang_terlambat' => 'nullable|numeric|min:0',
            'mangkir' => 'nullable|integer|min:0',
            'uang_mangkir' => 'nullable|numeric|min:0',
            'izin' => 'nullable|integer|min:0',
            'uang_izin' => 'nullable|numeric|min:0',

            'bayar_kasbon' => 'nullable|numeric|min:0',
            'potongan_bpjs_kesehatan' => 'nullable|numeric|min:0',
            'potongan_bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'loss' => 'nullable|numeric|min:0',

            'total_pengurangan' => 'required|numeric|min:0',
            'gaji_diterima' => 'required|numeric|min:0',

            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
        ]);

        $pegawai = Pegawai::findOrFail($request->pegawai_id);

        // Hitung total diterima dari frontend
        $total = $request->total_diterima;

        $now = Carbon::now();
        $year = $now->year;
        $month = $now->format('m');

        $lastPayroll = Payroll::where('company_id', $pegawai->company_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderByDesc('id')
            ->first();

        $sequence = 1;

        if ($lastPayroll && $lastPayroll->nomor_gaji) {
            $sequence = ((int) substr($lastPayroll->nomor_gaji, -5)) + 1;
        }

        $nomorGaji = sprintf(
            'PAY-%s-%s-%05d',
            $year,
            $month,
            $sequence
        );

        $payroll = Payroll::create([
            'company_id' => $pegawai->company_id,
            'pegawai_id' => $request->pegawai_id,
            'divisi_id' => $pegawai->divisi_id,
            'tanggal_bergabung' => $pegawai->tgl_join,
            'rekening' => $request->rekening,
            'kehadiran_100' => $request->input('kehadiran_100', 0),


            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'periode' => $request->periode_awal . ' - ' . $request->periode_akhir,
            'nomor_gaji' => $nomorGaji,

            'gaji_pokok' => $request->gaji_pokok,
            'uang_transport' => $request->uang_transport ?? 0,
            'lembur' => $request->lembur ?? 0,          
            'uang_lembur' => $request->uang_lembur ?? 0, 


            'bonus_kehadiran' => $request->bonus_kehadiran ?? 0,
            'bonus_pribadi' => $request->bonus_pribadi ?? 0,
            'bonus_team' => $request->bonus_team ?? 0,
            'bonus_jackpot' => $request->bonus_jackpot ?? 0,

            'thr' => $request->thr ?? 0,
            'tunjangan_hari_raya' => $request->tunjangan_hari_raya ?? 0,
            'reimbursement' => $request->reimbursement ?? 0,
            'tunjangan_bpjs_kesehatan' => $request->tunjangan_bpjs_kesehatan ?? 0,
            'tunjangan_bpjs_ketenagakerjaan' => $request->tunjangan_bpjs_ketenagakerjaan ?? 0,
            'tunjangan_pajak' => $request->tunjangan_pajak ?? 0,

            'total_tambah' => $request->total_tambah,

            'terlambat' => $request->terlambat ?? 0,
            'uang_terlambat' => $request->uang_terlambat ?? 0,
            'mangkir' => $request->mangkir ?? 0,
            'uang_mangkir' => $request->uang_mangkir ?? 0,
            'izin' => $request->izin ?? 0,
            'uang_izin' => $request->uang_izin ?? 0,

            'bayar_kasbon' => $request->bayar_kasbon ?? 0,
            'potongan_bpjs_kesehatan' => $request->potongan_bpjs_kesehatan ?? 0,
            'potongan_bpjs_ketenagakerjaan' => $request->potongan_bpjs_ketenagakerjaan ?? 0,
            'loss' => $request->loss ?? 0,

            'total_pengurangan' => $request->total_pengurangan,
            'gaji_diterima' => $request->gaji_diterima,

            'status' => $request->status ?? 'final',
            'keterangan' => $request->keterangan,
        ]);


        return response()->json([
            'message' => 'Payroll berhasil dibuat',
            'data' => $payroll
        ]);
    }

    // 🔹 Show single payroll
    public function show($id)
    {
        $payroll = Payroll::with([
            'pegawai.divisi',
            'company',
            'rekapPajak'
        ])->findOrFail($id);

        return response()->json($payroll);
    }


    // 🔹 Update payroll
    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer',
            'thr' => 'nullable|integer|min:0',
            'uang_transport' => 'nullable|numeric|min:0',

            'gaji_pokok' => 'required|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'uang_lembur' => 'nullable|numeric|min:0',

            'bonus_kehadiran' => 'nullable|numeric|min:0',
            'bonus_pribadi' => 'nullable|numeric|min:0',
            'bonus_team' => 'nullable|numeric|min:0',
            'bonus_jackpot' => 'nullable|numeric|min:0',

            'tunjangan_hari_raya' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
            'tunjangan_bpjs_kesehatan' => 'nullable|numeric|min:0',
            'tunjangan_bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'tunjangan_pajak' => 'nullable|numeric|min:0',

            'total_tambah' => 'required|numeric|min:0',

            'terlambat' => 'nullable|integer|min:0',
            'uang_terlambat' => 'nullable|numeric|min:0',
            'mangkir' => 'nullable|integer|min:0',
            'uang_mangkir' => 'nullable|numeric|min:0',
            'izin' => 'nullable|integer|min:0',
            'uang_izin' => 'nullable|numeric|min:0',

            'bayar_kasbon' => 'nullable|numeric|min:0',
            'potongan_bpjs_kesehatan' => 'nullable|numeric|min:0',
            'potongan_bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'loss' => 'nullable|numeric|min:0',

            'total_pengurangan' => 'required|numeric|min:0',
            'gaji_diterima' => 'required|numeric|min:0',

            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
            'status' => 'nullable|in:draft,final',
            'keterangan' => 'nullable|string',
        ]);

        $payroll->update([
            ...$request->except(['periode_awal', 'periode_akhir',]),
            'periode' => $request->periode_awal . ' - ' . $request->periode_akhir,
        ]);

        return response()->json([
            'message' => 'Payroll berhasil diperbarui',
            'data' => $payroll
        ]);
    }


    // 🔹 Delete payroll
    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();

        return response()->json([
            'message' => 'Payroll berhasil dihapus'
        ]);
    }

    public function download($id)
    {
        $payroll = Payroll::with([
            'pegawai.divisi'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.slip-gaji', [
            'payroll' => $payroll
        ])->setPaper('A4');

        return $pdf->download('slip-gaji-'.$payroll->nomor_gaji.'.pdf');
    }

    public function downloadPdf($id)
    {
        $penggajian = Payroll::with([
            'pegawai.divisi'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.penggajian', [
            'data' => $penggajian
        ])->setPaper('A4', 'portrait');

        return $pdf->download(
            'Slip-Gaji-' . $penggajian->pegawai->name . '-' . $penggajian->bulan . '.pdf'
        );
    }

    public function pdfSlip($id)
    {
        $payroll = Payroll::with(['pegawai.divisi', 'company'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.slip-gaji', compact('payroll'))
            ->setPaper('A4', 'portrait');

        return $pdf->download(
            'Slip-Gaji-' . $payroll->pegawai->name . '.pdf'
        );
    }
}
