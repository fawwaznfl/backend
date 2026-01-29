<?php 

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class UpdatePenugasanRequest extends FormRequest { 

    public function authorize(): bool { 
        return true; 
    } 
    
    public function rules(): array { 
        return ['pegawai_id'=>'nullable|exists:pegawai,id',
                'judul'=>'sometimes|required|string',
                'deskripsi'=>'nullable|string',
                'tanggal_mulai'=>'nullable|date',
                'tanggal_selesai'=>'nullable|date|after_or_equal:tanggal_mulai']; 
    } 
}