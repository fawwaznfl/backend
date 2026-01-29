<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatroliRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'tujuan' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'bukti_patrol' => 'nullable|string|max:255',
            'status' => 'nullable|in:berlangsung,selesai,batal',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ];
    }
}
