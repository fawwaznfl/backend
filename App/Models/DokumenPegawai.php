<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPegawai extends Model
{
    protected $fillable = [
        'pegawai_id',
        'company_id',
        'nama_dokumen',
        'file',
        'keterangan',
        'tanggal_upload',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return asset('storage/dokumen/'.$this->file);
    }
}
