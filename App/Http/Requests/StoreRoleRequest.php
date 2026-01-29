<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'company_id' => 'nullable|exists:companies,id',
        ];
    }
}
