<?php 

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class StoreKunjunganRequest extends FormRequest { 
    
    public function authorize(): bool { 
        return true; 
    } 
    
    public function rules(): array { 
        return ['pegawai_id'=>'required|exists:pegawai,id',
                'tanggal'=>'required|date',
                'tempat'=>'nullable|string',
                'keterangan'=>'nullable|string']; 
        } 
    }