<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePegawaiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => 'nullable|string|max:191',
            'foto_karyawan' => 'nullable|image|max:2048',
            'email' => "nullable|email|unique:pegawais,email,$id",
            'company_id' => 'nullable|exists:companies,id',
            'telepon' => 'nullable|string|max:30',
            'username' => "nullable|string|max:191|unique:pegawais,username,$id",
            'lokasi_id' => 'nullable|exists:lokasis,id',

            'tgl_lahir' => 'nullable|date',
            'tgl_join' => 'nullable|date',
            'masa_kerja' => 'nullable|string|max:100',

            'role_id' => 'nullable|exists:roles,id',
            'gender' => 'nullable|in:L,P',

            'dashboard_type' => 'nullable|string|max:50',

            'status_nikah' => 'nullable|in:menikah,belum_menikah',

            'divisi_id' => 'nullable|exists:divisis,id',

            'status_pajak' => 'nullable|string|max:100',

            // DATA IDENTITAS
            'ktp' => 'nullable|string|max:100',
            'kartu_keluarga' => 'nullable|string|max:100',
            'bpjs_kesehatan' => 'nullable|string|max:100',
            'bpjs_ketenagakerjaan' => 'nullable|string|max:100',
            'npwp' => 'nullable|string|max:100',
            'sim' => 'nullable|string|max:100',

            // KONTRAK KERJA
            'no_pkwt' => 'nullable|string|max:191',
            'no_kontrak' => 'nullable|string|max:191',
            'tanggal_mulai_pwkt' => 'nullable|date',
            'tanggal_berakhir_pwkt' => 'nullable|date',
            'masa_berlaku' => 'nullable|date',

            // REKENING
            'rekening' => 'nullable|string|max:100',
            'nama_rekening' => 'nullable|string|max:191',

            // ALAMAT
            'alamat' => 'nullable|string',

            // IZIN & CUTI
            'izin_cuti' => 'nullable|integer|min:0',
            'izin_lainnya' => 'nullable|integer|min:0',
            'izin_telat' => 'nullable|integer|min:0',
            'izin_pulang_cepat' => 'nullable|integer|min:0',

            // GAJI & TUNJANGAN
            'gaji_pokok' => 'nullable|numeric|min:0',
            'makan_transport' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'kehadiran' => 'nullable|numeric|min:0',
            'thr' => 'nullable|numeric|min:0',

            // BONUS
            'bonus_pribadi' => 'nullable|numeric|min:0',
            'bonus_team' => 'nullable|numeric|min:0',
            'bonus_jackpot' => 'nullable|numeric|min:0',

            // ABSENSI
            'izin' => 'nullable|integer|min:0',
            'terlambat' => 'nullable|integer|min:0',
            'mangkir' => 'nullable|integer|min:0',

            // KASBON
            'saldo_kasbon' => 'nullable|numeric|min:0',

            // TUNJANGAN BPJS & PAJAK
            'tunjangan_bpjs_kesehatan' => 'nullable|numeric|min:0',
            'tunjangan_bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'potongan_bpjs_kesehatan' => 'nullable|numeric|min:0',
            'potongan_bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'tunjangan_pajak' => 'nullable|numeric|min:0',

            // STATUS PEGAWAI
            'status' => 'nullable|string|max:100',
        ];
    }
}
