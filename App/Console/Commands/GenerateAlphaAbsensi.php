<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\ShiftMapping;
use Illuminate\Console\Command;

class GenerateAlphaAbsensi extends Command
{
    protected $signature = 'generate:alpha-absensi';
    protected $description = 'Generate absensi alpha otomatis';
    
    public function handle()
    {
        $tanggal = now()->subDay()->toDateString(); // KEMARIN

        $shiftMappings = ShiftMapping::whereDate('tanggal_mulai', $tanggal)->get();

        foreach ($shiftMappings as $mapping) {

            $pegawaiId = $mapping->pegawai_id;

            // ❌ Skip jika cuti
            $isCuti = Cuti::where('pegawai_id', $pegawaiId)
                ->whereDate('tanggal_mulai', '<=', $tanggal)
                ->whereDate('tanggal_selesai', '>=', $tanggal)
                ->where('status', 'disetujui')
                ->exists();

            if ($isCuti) continue;

            // 🔥 Ambil absensi kemarin
            $absensi = Absensi::where('pegawai_id', $pegawaiId)
                ->whereDate('tanggal', $tanggal)
                ->first();

            // ❌ Kalau sudah absen masuk (meskipun belum pulang)
            // → jangan alpha
            if ($absensi && $absensi->jam_masuk) {
                continue;
            }

            // ✅ INSERT ALPHA
            Absensi::create([
                'pegawai_id' => $pegawaiId,
                'company_id' => $mapping->company_id,
                'lokasi_id'  => null,
                'shift_id'   => $mapping->shift_id,
                'tanggal'    => $tanggal,
                'status'     => 'alpha',
                'keterangan' => 'Tidak melakukan absensi',
            ]);
        }

        $this->info('Alpha absensi berhasil digenerate.');
    }


}
