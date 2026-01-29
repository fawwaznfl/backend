<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBeritaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'tipe' => 'required|string|in:informasi,berita',
            'judul' => 'required|string|max:255',
            'isi_konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tanggal_publikasi' => 'required|date',
        ];
    }
}
