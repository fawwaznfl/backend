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
            ->where('company_id', $pegawai->company_id ?? null)
            ->where('status', 'disetujui')
            ->whereBetween('tanggal_lembur', [$startDate, $endDate])
            ->sum('total_lembur_menit');

        // HITUNG LEMBUR (JAM + MENIT)
        $jam = intdiv($totalLemburMenit, 60);
        $menit = $totalLemburMenit % 60;

        $lemburJamTampilan = $jam;
        $lemburJamDibayar = $jam;

        if ($menit > 30) {
            $lemburJamTampilan = $jam + 1;
            $lemburJamDibayar = $jam + 1;
        } elseif ($menit == 30) {
            $lemburJamTampilan = $jam + 0.5;
            $lemburJamDibayar = $jam + 0.5;
        } else {
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
            ->count();

        // HITUNG TERLAMBAT

        $terlambatSatuan = $pegawai->terlambat_satuan ?? 'hari';

        $terlambatCount = 0;

        if ($terlambatSatuan === 'hari') {

            // HITUNG PER HARI TELAT
            $terlambatCount = DB::table('absensis')
                ->where('pegawai_id', $pegawaiId)
                ->where('company_id', $pegawai->company_id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('telat', '>', 0)
                ->distinct(DB::raw('DATE(tanggal)'))
                ->count(DB::raw('DATE(tanggal)'));

        } elseif ($terlambatSatuan === 'menit') {

            // TOTAL MENIT TELAT
            $terlambatCount = DB::table('absensis')
                ->where('pegawai_id', $pegawaiId)
                ->where('company_id', $pegawai->company_id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('telat');

        } elseif ($terlambatSatuan === 'jam') {

            // TOTAL JAM TELAT (BULAT ATAS)
            $totalMenitTelat = DB::table('absensis')
                ->where('pegawai_id', $pegawaiId)
                ->where('company_id', $pegawai->company_id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('telat');

            $terlambatCount = (int) ceil($totalMenitTelat / 60);
        }


        // HITUNG KASBON YANG APPROVE DALAM PERIODE
        $bayarKasbonPeriode = DB::table('kasbons')
            ->where('pegawai_id', $pegawaiId)
            ->where('company_id', $pegawai->company_id)
            ->where('status', 'approve')
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('nominal');

        // HITUNG TOTAL SEMUA KASBON (APPROVE + PAID)
        $totalSemuaKasbon = DB::table('kasbons')
            ->where('pegawai_id', $pegawaiId)
            ->where('company_id', $pegawai->company_id)
            ->whereIn('status', ['approve', 'paid'])
            ->sum('nominal');

        return [
            'nama_pegawai' => $pegawai->name, 
            'jabatan' => $pegawai->nama ?? '-',
            'rekening' => $pegawai->rekening,
            'bulan' => $startDate->translatedFormat('F'),
            'tahun' => $tahun,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'tgl_join' => $pegawai->tgl_join ?? null,
            'company_id' => $pegawai->company_id,
            'persentase_kehadiran' => $persentase,
            
            // DATA GAJI
            'gaji_pokok' => $pegawai->gaji_pokok,
            'makan_transport' => $pegawai->makan_transport,
            'total_reimbursement' => $reimbursementTotal,
            
            // BONUS
            'bonus_pribadi' => $pegawai->bonus_pribadi,
            'bonus_team' => $pegawai->bonus_team,
            'bonus_jackpot' => $pegawai->bonus_jackpot,
            
            // ABSENSI COUNT
            'mangkir_count' => $mangkirCount,
            'lembur_count' => $lemburJamTampilan,
            'lembur_jam_dibayar' => $lemburJamDibayar,
            'izin_masuk_count' => $izinMasukCount,
            'terlambat_count' => $terlambatCount,
            'terlambat_satuan' => $terlambatSatuan,
            'kehadiran_count' => 0, // Sesuaikan jika ada logika khusus
            
            // UANG PER ITEM
            'mangkir' => $pegawai->mangkir,
            'lembur' => $pegawai->lembur,
            'izin' => $pegawai->izin,
            'terlambat' => $pegawai->terlambat,
            'kehadiran' => $pegawai->kehadiran,
            
            // KASBON
            'saldo_kasbon' => $totalSemuaKasbon,
            'bayar_kasbon_periode' => $bayarKasbonPeriode,
            
            // TUNJANGAN
            'tunjangan_bpjs_kesehatan' => $pegawai->tunjangan_bpjs_kesehatan,
            'tunjangan_bpjs_ketenagakerjaan' => $pegawai->tunjangan_bpjs_ketenagakerjaan,
            'tunjangan_pajak' => $pegawai->tunjangan_pajak,
            
            // POTONGAN
            'potongan_bpjs_kesehatan' => $pegawai->potongan_bpjs_kesehatan,
            'potongan_bpjs_ketenagakerjaan' => $pegawai->potongan_bpjs_ketenagakerjaan,
            
            // THR
            'thr' => $pegawai->thr,
            'thr_count' => 0, 
        ];
    }
}