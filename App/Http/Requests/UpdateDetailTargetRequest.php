<?php 

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailTargetRequest extends FormRequest { 
    
    public function authorize(): bool { 
        return true; 
    } 
    
    public function rules(): array { 
        return ['target_kinerja_id'=>'nullable|exists:target_kinerjas,id',
                'item'=>'sometimes|required|string',
                'target'=>'nullable|integer',
                'realisasi'=>'nullable|integer']; 
    } 
}