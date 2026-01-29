<?php 

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class UpdateTargetKinerjaRequest extends FormRequest { 
    
    public function authorize(): bool { 
        return true; 
    } 
    
    public function rules(): array { 
        return ['pegawai_id'=>'nullable|exists:pegawai,id',
                'judul'=>'sometimes|required|string',
                'deadline'=>'nullable|date']; 
    } 
}