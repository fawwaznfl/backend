<?php

namespace App\Models;

use App\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'pegawai_id',
        'tanggal_keluar',
        'alasan',
        'jenis_keberhentian',
        'upload_file',
        'status',
        'note_approver',
        'disetujui_oleh',
        'created_by',
        'updated_by',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function getUploadFileUrlAttribute()
    {
        if (!$this->upload_file) return null;

        return asset('storage/' . $this->upload_file);
    }

}
