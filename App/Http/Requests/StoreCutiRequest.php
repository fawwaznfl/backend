<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCutiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_cuti' => 'required|in:tahunan,sakit,melahirkan,penting,lainnya',
            'alasan' => 'nullable|string',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ];
    }
}
