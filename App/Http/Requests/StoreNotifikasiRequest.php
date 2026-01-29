<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'isi' => 'nullable|string',
            'tipe' => 'nullable|in:info,warning,urgent',
            'company_id' => 'nullable|exists:companies,id',
            'user_id' => 'nullable|exists:users,id',
            'is_broadcast' => 'boolean',
            'tanggal_kirim' => 'nullable|date',
        ];
    }
}
