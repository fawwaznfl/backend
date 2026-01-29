<?php 

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class StoreDetailTargetRequest extends FormRequest { 
    public function authorize(): bool { 
        return true; } public function rules(): array { 
        return ['target_kinerja_id'=>'required|exists:target_kinerjas,id',          
                'item'=>'required|string',
                'target'=>'nullable|integer',
                'realisasi'=>'nullable|integer']; 
    }
}