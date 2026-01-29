<?php

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class UpdateKunjunganRequest extends FormRequest { 
    
    public function authorize(): bool { 
        return true; 
    } 
    
    public function rules(): array { 
        return ['pegawai_id'=>'nullable|exists:pegawai,id',
                'tanggal'=>'nullable|date',
                'tempat'=>'nullable|string',
                'keterangan'=>'nullable|string']; 
        } 
    }