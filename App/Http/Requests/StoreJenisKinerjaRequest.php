<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisKinerjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'nama' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'aktif' => 'nullable|boolean',
            'bobot_penilaian' => 'required|numeric|min:-100|max:100',
        ];
    }
}
