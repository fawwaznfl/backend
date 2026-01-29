<?php 

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDivisiRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'nama' => 'sometimes|required|string|max:150',
            'deskripsi' => 'nullable|string',
        ];
    }
}