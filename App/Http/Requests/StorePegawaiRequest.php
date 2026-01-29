<?php 

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StorePegawaiRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'company_id'=>'nullable|exists:companies,id',
            'nik'=>'nullable|string|max:50|unique:pegawais,nik',
            'name'=>'required|string|max:191',
            'email' => 'nullable|email|unique:pegawais,email',
            'phone'=>'nullable|string|max:30',
            'divisi_id'=>'nullable|exists:divisis,id',
            'shift_id'=>'nullable|exists:shifts,id',
            'lokasi_id'=>'nullable|exists:lokasis,id',
            'status'=>'nullable|string',
        ];
    }
}