<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePegawaiKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_keluar' => 'sometimes|date',
            'alasan' => 'nullable|string|max:255',
            'jenis_keberhentian' => 'nullable|in:PHK,Pengunduran Diri,Meninggal Dunia,Pensiun',
            'upload_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'disetujui_oleh' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ];
    }
}
