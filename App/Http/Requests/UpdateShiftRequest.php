<?php 

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest 
    { 
        public function authorize(): bool 
        { 
            return true; 
        
        } public function rules(): array 
        
        { return ['nama'=>'sometimes|required|string|max:150',
                  'mulai'=>'nullable|date_format:H:i',
                  'selesai'=>'nullable|date_format:H:i']; 
        } 
    }
