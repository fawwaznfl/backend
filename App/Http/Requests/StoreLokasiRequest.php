<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLokasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'nama_lokasi' => 'required|string|max:100',
            'lat_kantor' => 'nullable|string',
            'long_kantor' => 'nullable|string',
            'radius' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }
}
