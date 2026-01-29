<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDinasLuarRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',

            'foto_masuk' => 'nullable|string|max:255',
            'foto_pulang' => 'nullable|string|max:255',

            'lokasi_masuk' => 'nullable|string|max:255',
            'lokasi_pulang' => 'nullable|string|max:255',

            'telat' => 'nullable|integer',
            'pulang_cepat' => 'nullable|integer',

            'status' => 'nullable|in:hadir,sakit,izin,cuti,dinas_luar,libur,alpha',
            'verifikasi' => 'nullable|in:pending,disetujui,ditolak',

            'approved_by' => 'nullable|exists:pegawais,id',

            'keterangan' => 'nullable|string',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }
}
