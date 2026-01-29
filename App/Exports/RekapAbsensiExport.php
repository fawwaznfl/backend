<?php

namespace App\Exports;

use App\Models\Pegawai;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsensiExport implements FromCollection, WithHeadings
{
    protected $start;
    protected $end;
    protected $companyId;
    protected $pegawaiId;

    public function __construct($start, $end, $companyId = null, $pegawaiId = null)
    {
        $this->start = Carbon::parse($start);
        $this->end = Carbon::parse($end);
        $this->companyId = $companyId;
        $this->pegawaiId = $pegawaiId;
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Shift',
            'Tanggal',
            'Jam Masuk',
            'Telat',
            'Keterangan Masuk',
            'Jam Pulang',
            'Pulang Cepat',
            'Keterangan Pulang',
            'Status Absen',
        ];
    }

    public function collection(): Collection
    {
        $rows = collect();

        // 🔥 Pegawai TANPA relasi shift
        $pegawaiQuery = Pegawai::query();

        if ($this->pegawaiId) {
            $pegawaiQuery->where('id', $this->pegawaiId);
        } elseif ($this->companyId) {
            $pegawaiQuery->where('company_id', $this->companyId);
        }

        $pegawaiList = $pegawaiQuery->get();

        foreach ($pegawaiList as $pegawai) {
            $current = $this->start->copy();

            while ($current <= $this->end) {

                // 🔥 Ambil absensi + shift HARIAN
                $absen = Absensi::with('shift')
                    ->where('pegawai_id', $pegawai->id)
                    ->whereDate('tanggal', $current->toDateString())
                    ->first();

                // ================= SHIFT FORMAT =================
                $shiftText = '-';

                if ($absen && $absen->shift) {
                    $shiftText =
                        $absen->shift->nama_shift . ' ' .
                        substr($absen->shift->jam_masuk, 0, 5) .
                        ' - ' .
                        substr($absen->shift->jam_pulang, 0, 5);
                }

                // ================= PUSH ROW =================
                if ($absen) {
                    $rows->push([
                        $pegawai->name,
                        $shiftText,
                        $current->toDateString(),
                        $absen->jam_masuk ?? '-',
                        $absen->telat
                            ? gmdate('H \J\a\m i \M\e\n\i\t s \D\e\t\i\k', $absen->telat * 60)
                            : '-',
                        $absen->keterangan_masuk ?? '',
                        $absen->jam_pulang ?? '-',
                        $absen->pulang_cepat
                            ? gmdate('H \J\a\m i \M\e\n\i\t', $absen->pulang_cepat * 60)
                            : '-',
                        $absen->keterangan_pulang ?? '',
                        'Masuk',
                    ]);
                } else {
                    $rows->push([
                        $pegawai->name,
                        '-',
                        $current->toDateString(),
                        '-',
                        '-',
                        '',
                        '-',
                        '-',
                        '',
                        'Tidak Masuk',
                    ]);
                }

                $current->addDay();
            }
        }

        return $rows;
    }
}
