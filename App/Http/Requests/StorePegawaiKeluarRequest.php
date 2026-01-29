<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePegawaiKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ubah kalau pakai policy
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'tanggal_keluar' => 'required|date',
            'alasan' => 'nullable|string|max:255',
            'jenis_keberhentian' => 'nullable|in:PHK,Pengunduran Diri,Meninggal Dunia,Pensiun',
            'upload_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'disetujui_oleh' => 'nullable|exists:pegawais,id',
            'created_by' => 'nullable|exists:users,id',
        ];
    }
}
