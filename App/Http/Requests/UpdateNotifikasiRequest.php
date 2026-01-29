<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => 'sometimes|required|string|max:255',
            'isi' => 'nullable|string',
            'tipe' => 'nullable|in:info,warning,urgent',
            'is_broadcast' => 'boolean',
            'tanggal_kirim' => 'nullable|date',
        ];
    }
}
