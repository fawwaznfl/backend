<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
//            'kode_shift' => 'nullable|string|max:50',
            'nama' => 'required|string|max:100',
  //          'jenis_shift' => 'required|in:pagi,siang,malam,custom',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i|after:jam_masuk',
   //         'tanggal_mulai' => 'nullable|date',
     //       'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
       //     'durasi_jam' => 'nullable|integer|min:0',
         //   'is_libur' => 'boolean',
           // 'keterangan' => 'nullable|string',
        ];
    }
}
