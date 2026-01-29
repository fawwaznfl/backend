<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCutiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pegawai_id' => 'sometimes|exists:pegawais,id',
            'tanggal_mulai' => 'sometimes|date',
            'tanggal_selesai' => 'sometimes|date|after_or_equal:tanggal_mulai',
            'jenis_cuti' => 'sometimes|in:tahunan,sakit,melahirkan,penting,lainnya',
            'alasan' => 'nullable|string',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ];
    }
}
