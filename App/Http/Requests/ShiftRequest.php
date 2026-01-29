<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'nama'       => 'required|string|max:100',
            'jam_masuk'  => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
        ];
    }
}
