<?php

namespace App\Services;

use App\Models\Reimbursement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RekapAbsensiService
{
    
    public function rekapPegawai(Request $request, $pegawaiId)
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();


        // DATA PEGAWAI
        $pegawai = DB::table('pegawais')
            ->leftJoin('divisis', 'divisis.id', '=', 'pegawais.divisi_id')
            ->where('pegawais.id', $pegawaiId)
            ->select(
                'pegawais.*',
                'divisis.nama'
            )
            ->first();


        if (!$pegawai) {
            throw new \Exception("Pegawai tidak ditemukan");
        }

        // HITUNG ABSENSI (PAKAI absensis)
        $hadir = DB::table('absensis')
            ->where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereNotNull('jam_masuk')
            ->count();

        $totalHariKerja = DB::table('absensis')
            ->where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        $persentase = $totalHariKerja > 0
            ? round(($hadir / $totalHariKerja) * 100, 2)
            : 0;

        $reimbursementTotal = Reimbursement::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('total');

        // TOTAL HARI DARI RANGE TANGGAL (INKLUSIF)
        $totalHari = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;

        // TOTAL HADIR (UNIK PER TANGGAL)
        $tanggalHadir = DB::table('absensis')
            ->where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereNotNull('jam_masuk')
            ->selectRaw('DATE(tanggal) as tanggal')
            ->groupByRaw('DATE(tanggal)')
            ->pluck('tanggal')
            ->toArray();

        // HITUNG MANGKIR
        $totalHadir = count($tanggalHadir);
        $mangkirCount = max($totalHari - $totalHadir, 0);

        // HITUNG TOTAL LEMBUR (MENIT)
        $totalLemburMenit = DB::table('lemburs')
            ->where('pegawai_id', $pegawaiId)
            ->where('company_id', $pegawai->company_id ?? null) // opsional kalau ada
            ->where('status', 'disetujui')
            ->whereBetween('tanggal_lembur', [$startDate, $endDate])
            ->sum('total_lembur_menit');

        // ===============================
        // HITUNG LEMBUR (JAM + MENIT)
        // ===============================
        $jam = intdiv($totalLemburMenit, 60);
        $menit = $totalLemburMenit % 60;

        // default
        $lemburJamTampilan = $jam;
        $lemburJamDibayar = $jam;

        // aturan pembulatan
        if ($menit > 30) {
            // contoh 3j 50m → 4 jam
            $lemburJamTampilan = $jam + 1;
            $lemburJamDibayar = $jam + 1;
        } elseif ($menit == 30) {
            // contoh 3j 30m → 3.5 jam
            $lemburJamTampilan = $jam + 0.5;
            $lemburJamDibayar = $jam + 0.5;
        } else {
            // contoh 3j 15m → 3 jam
            $lemburJamTampilan = $jam;
            $lemburJamDibayar = $jam;
        }

        

        // HITUNG IZIN MASUK
        $izinMasukCount = DB::table('cutis')
            ->where('pegawai_id', $pegawaiId)
            ->where('company_id', $pegawai->company_id)
            ->where('jenis_cuti', 'izin_masuk')
            ->where('status', 'disetujui')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_mulai', [$startDate, $endDate])
                ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                ->orWhere(function ($q2) use ($startDate, $endDate) {
                    $q2->where('tanggal_mulai', '<=', $startDate)
                        ->where('tanggal_selesai', '>=', $endDate);
                });
            })
            ->count(); // 🔥 PER ROW / PER KALI


        // HITUNG TERLAMBAT
        $terlambatCount = DB::table('absensis')
            ->where('pegawai_id', $pegawaiId)
            ->where('company_id', $pegawai->company_id)
            ->whereDate('tanggal', '>=', $startDate->toDateString())
            ->whereDate('tanggal', '<=', $endDate->toDateString())
            ->where('telat', '>', 0)
            ->distinct()
            ->count('tanggal');


        return [
            'nama_pegawai' => $pegawai->name, 
            'jabatan' => $pegawai->nama ?? '-',
            'rekening' => $pegawai->rekening,
            'bulan' => $startDate->translatedFormat('F'),
            'tahun' => $tahun,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'gaji_pokok'           => $pegawai->gaji_pokok,
            'makan_transport'           => $pegawai->makan_transport,
            'total_reimbursement' => $reimbursementTotal,
            'mangkir'           => $pegawai->mangkir,
            'lembur'           => $pegawai->lembur,
            'izin'           => $pegawai->izin,
            'bonus_pribadi'           => $pegawai->bonus_pribadi,
            'bonus_team'           => $pegawai->bonus_team,
            'bonus_jackpot'           => $pegawai->bonus_jackpot,
            'terlambat'           => $pegawai->terlambat,
            'kehadiran'           => $pegawai->kehadiran,
            'saldo_kasbon'           => $pegawai->saldo_kasbon,
            'tunjangan_bpjs_kesehatan'           => $pegawai->tunjangan_bpjs_kesehatan,
            'tunjangan_bpjs_ketenagakerjaan'           => $pegawai->tunjangan_bpjs_ketenagakerjaan,
            'tunjangan_pajak'           => $pegawai->tunjangan_pajak,
            'potongan_bpjs_kesehatan'           => $pegawai->potongan_bpjs_kesehatan,
            'potongan_bpjs_ketenagakerjaan'           => $pegawai->potongan_bpjs_ketenagakerjaan,
            'thr'           => $pegawai->thr,
            'mangkir_count' => $mangkirCount,
            'lembur_count' => $lemburJamTampilan,
            'lembur_jam_dibayar' => $lemburJamDibayar,
            //'lembur' => $pegawai->lembur * $lemburJam, 
            'izin_masuk_count' => $izinMasukCount,
            //'izin' => $pegawai->izin * $izinMasukCount,
            'terlambat_count' => $terlambatCount,
            

        ];
    }
}
