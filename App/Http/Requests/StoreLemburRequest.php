<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLemburRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'pegawai_id' => 'required|exists:pegawais,id',

            'tanggal_lembur' => 'required|date',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',

            'foto_masuk' => 'nullable|string|max:255',
            'foto_pulang' => 'nullable|string|max:255',

            'lokasi_masuk' => 'nullable|string|max:255',
            'lokasi_pulang' => 'nullable|string|max:255',
            'status' => 'nullable|in:menunggu,disetujui,ditolak',
            'disetujui_oleh' => 'nullable|exists:pegawais,id',

            'keterangan' => 'nullable|string',
        ];
    }
}
