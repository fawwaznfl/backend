<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreKontrakRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'company_id' => 'required|exists:companies,id',
            'pegawai_id'=>'required|exists:pegawais,id',
            'tanggal_mulai'=>'nullable|date',
            'tanggal_selesai'=>'nullable|date|after_or_equal:tanggal_mulai',
            'jenis_kontrak' => 'required|string',
            'keterangan'=>'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}