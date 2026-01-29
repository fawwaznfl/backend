<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDinasLuarRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'pegawai_id' => 'required|exists:pegawais,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'lokasi_id' => 'nullable|exists:lokasis,id',

            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',

            // === Foto Masuk & Pulang (string path atau file)
            'foto_masuk' => 'nullable|string|max:255',
            'foto_pulang' => 'nullable|string|max:255',

            // === Lokasi Masuk & Pulang
            'lokasi_masuk' => 'nullable|string|max:255',
            'lokasi_pulang' => 'nullable|string|max:255',

            // === Hitungan telat / pulang cepat (integer)
            'telat' => 'nullable|integer',
            'pulang_cepat' => 'nullable|integer',

            'status' => 'required|in:hadir,sakit,izin,cuti,dinas_luar,libur,alpha',
            'verifikasi' => 'nullable|in:pending,disetujui,ditolak',

            'approved_by' => 'nullable|exists:pegawais,id',

            'keterangan' => 'nullable|string',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }
}
