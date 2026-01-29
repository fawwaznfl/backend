<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatroliRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ubah ke policy jika ingin dibatasi
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tujuan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'bukti_patrol' => 'nullable|string|max:255',
            'status' => 'nullable|in:berlangsung,selesai,batal',
        ];
    }

    public function messages(): array
    {
        return [
            'pegawai_id.required' => 'Pegawai wajib diisi.',
            'pegawai_id.exists' => 'Pegawai tidak ditemukan.',
            'tujuan.required' => 'Tujuan patroli wajib diisi.',
            'tanggal.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ];
    }
}
