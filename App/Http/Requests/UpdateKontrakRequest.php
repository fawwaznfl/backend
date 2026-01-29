<?php 

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class UpdateKontrakRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'pegawai_id'=>'nullable|exists:pegawai,id',
            'tanggal_mulai'=>'nullable|date',
            'tanggal_selesai'=>'nullable|date|after_or_equal:tanggal_mulai',
            'jenis'=>'nullable|string',
            'keterangan'=>'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}