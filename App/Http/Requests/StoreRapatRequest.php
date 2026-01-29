<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRapatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'       => 'nullable|exists:companies,id',
            'nama_pertemuan'   => 'required|string|max:255',
            'detail_pertemuan' => 'required|string',
            'tanggal_rapat'    => 'required|date',
            'lokasi'           => 'required|string|max:255',
            'waktu_mulai'      => 'required|date_format:H:i',
            'waktu_selesai'    => 'required|date_format:H:i|after:waktu_mulai',
            'jenis_pertemuan'  => 'required|in:offline,online',
            'file_notulen'     => 'nullable|file|mimes:pdf,doc,docx',
            'pegawai_ids'      => 'required|array',
            'pegawai_ids.*'    => 'exists:pegawais,id',
        ];
    }

}
