<?php 

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class StoreTargetKinerjaRequest extends FormRequest { 

    public function authorize(): bool { 
        return true; 
} 

    public function rules(): array { 
        return ['pegawai_id'=>'required|exists:pegawai,id',
                'judul'=>'required|string',
                'deadline'=>'nullable|date']; 
    } 
}