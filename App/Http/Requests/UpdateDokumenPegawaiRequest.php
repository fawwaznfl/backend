<?php

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class UpdateDokumenPegawaiRequest extends FormRequest { 
    public function authorize(): bool { 
        return true; } public function rules(): array { 
        return ['pegawai_id'=>'nullable|exists:pegawai,id',
        'nama_file'=>'sometimes|required|string',
        'path'=>'sometimes|required|string',
        'tipe'=>'nullable|string']; 
    } 
}