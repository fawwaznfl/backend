<?php 

namespace App\Http\Requests; 
use Illuminate\Foundation\Http\FormRequest;

class UpdateRapatRequest extends FormRequest { 

    public function authorize(): bool { 
        return true; 
    } 
    
    public function rules(): array { 
        return [
                'company_id' => 'required|exists:companies,id',
                'nama_pertemuan' => 'sometimes|required|string',
                'detail_pertemuan' => 'nullable|string',
                'file_notulen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'tanggal_rapat' => 'nullable|date',
                'waktu_mulai' => 'nullable|date_format:H:i',
                'waktu_selesai' => 'nullable|date_format:H:i',
                'lokasi' => 'nullable|string',
                'jenis_pertemuan' => 'nullable|in:online,offline',
            ];
    } 
}