<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReimbursementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'pegawai_id' => 'required|integer',
            'kategori_reimbursement_id' => 'required|exists:kategori_reimbursements,id',
            'tanggal' => 'required|date',
            'event' => 'required|string|max:255',
            'metode_reim' => 'required|in:cash,transfer',
            'no_rekening' => 'nullable|required_if:metode_reim,transfer|string|max:50',
            'jumlah' => 'nullable|integer|min:0',
            'terpakai' => 'nullable|integer|min:0',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

}
