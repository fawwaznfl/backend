<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJenisKinerjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'sometimes|string|max:255',
            'detail' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'aktif' => 'nullable|boolean',
            'bobot_penilaian' => 'sometimes|numeric|min:-100|max:100',
        ];
    }
}
