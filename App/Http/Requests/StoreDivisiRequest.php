<?php 

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreDivisiRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'nama' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
        ];
    }
}