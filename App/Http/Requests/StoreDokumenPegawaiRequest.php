<?php

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class StoreDokumenPegawaiRequest extends FormRequest { 
    public function authorize(): bool { 
        return true; } public function rules(): array { 
        return ['pegawai_id'=>'required|exists:pegawai,id','nama_file'=>'required|string','path'=>'required|string','tipe'=>'nullable|string']; 
    } 
}