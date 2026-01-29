<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('inventory');

        return [
            'company_id'     => 'required|exists:companies,id',
            'lokasi_id'      => 'nullable|exists:lokasis,id',
            'divisi_id'      => 'nullable|exists:divisis,id',
            'kode_barang'    => "required|string|unique:inventories,kode_barang,$id",
            'nama_barang'    => 'required|string|max:255',
            'stok'           => 'required|integer|min:0',
            'satuan'         => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
        ];
    }
}
