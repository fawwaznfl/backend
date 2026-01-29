<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLemburRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pegawai_id' => 'nullable|exists:pegawais,id',
            'company_id' => 'nullable|exists:companies,id',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'durasi_jam' => 'nullable|integer|min:1',
            'alasan' => 'nullable|string',
            'bukti_lembur' => 'nullable|file|max:2048',
            'status' => 'nullable|in:berlangsung,disetujui,ditolak,selesai',
        ];
    }
}
