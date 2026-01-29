<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsensiSummaryExport implements FromCollection, WithHeadings
{
    protected Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($r) {
            return [
                $r->nama_pegawai,
                $r->total_cuti . ' x',
                $r->total_izin_masuk . ' x',
                $r->total_izin_telat . ' x',
                $r->total_izin_pulang_cepat . ' x',
                $r->total_hadir . ' x',
                $r->total_alfa . ' x',
                $r->total_libur . ' x',
                $r->total_telat,
                $r->total_pulang_cepat,
                $r->total_lembur,
                $r->persentase_kehadiran . ' %',
            ];
        });
    }


    public function headings(): array
    {
        return [
            'Nama',
            'Total Cuti',
            'Total Izin Masuk',
            'Total Izin Telat',
            'Total Izin Pulang Cepat',
            'Total Hadir',
            'Total Alfa',
            'Total Libur',
            'Total Telat',
            'Total Pulang Cepat',
            'Total Lembur',
            'Persentase Kehadiran',
        ];
    }
}
