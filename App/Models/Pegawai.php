<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Penting agar bisa login
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pegawai extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pegawais';
    protected $appends = ['foto_karyawan_url'];

    protected $fillable = [
        'company_id', 'divisi_id', 'lokasi_id', 'shift_id',
        'name', 'username', 'email', 'telepon', 'password', 'role_id',
        'foto_karyawan', 'foto_face_recognition', 'tgl_lahir', 'gender',
        'tgl_join', 'status_nikah', 'alamat',
        'ktp', 'kartu_keluarga', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan',
        'npwp', 'sim', 'no_pkwt', 'no_kontrak', 'tanggal_mulai_pwkt',
        'tanggal_berakhir_pwkt', 'masa_berlaku', 'rekening', 'nama_rekening',
        'gaji_pokok', 'makan_transport', 'lembur', 'kehadiran', 'thr',
        'bonus_pribadi', 'bonus_team', 'bonus_jackpot', 'izin', 'terlambat',
        'mangkir', 'saldo_kasbon', 'status_pajak', 'tunjangan_bpjs_kesehatan', 'potongan_bpjs_kesehatan',
        'tunjangan_bpjs_ketenagakerjaan', 'potongan_bpjs_ketenagakerjaan','tunjangan_pajak', 'izin_cuti',
        'izin_lainnya', 'izin_telat', 'izin_pulang_cepat', 'status',
        'dashboard_type', 'email_verified_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tgl_join' => 'date',
        'tgl_lahir' => 'date',
        'tanggal_mulai_pwkt' => 'date',
        'tanggal_berakhir_pwkt' => 'date',
        'masa_berlaku' => 'date',
    ];

    public function getFotoKaryawanUrlAttribute()
    {
        return $this->foto_karyawan
            ? asset('storage/' . $this->foto_karyawan)
            : null;
    }

    

    // === Relasi opsional ===
    public function company() { return $this->belongsTo(Company::class); }
    public function divisi() { return $this->belongsTo(Divisi::class); }
    public function lokasi() { return $this->belongsTo(Lokasi::class); }
    public function shift() { return $this->belongsTo(Shift::class); }
    public function role() { return $this->belongsTo(Role::class); }
    public function pegawai(){return $this->belongsTo(Pegawai::class, 'pegawai_id');}
    public function lemburs(){return $this->hasMany(Lembur::class, 'pegawai_id');}
    public function absensis(){return $this->hasMany(Absensi::class, 'pegawai_id');}
    public function faces(){return $this->hasMany(PegawaiFace::class);}
    public function pegawaiKeluar(){ return $this->hasOne(PegawaiKeluar::class, 'pegawai_id');}


    public function rapats()
    {
        return $this->belongsToMany(
            Rapat::class,
            'rapat_pegawai',
            'pegawai_id',
            'rapat_id',
        )->withPivot(['waktu_hadir'])
        ->withTimestamps();
    }

    protected static function booted()
    {
        // Saat pegawai BARU dibuat notif ke SUPERADMIN
        static::created(function ($pegawai) {
            NotificationService::create(
                [
                    'type' => 'new_employee_created',
                    'title' => 'Pegawai Baru Ditambahkan',
                    'message' => "Pegawai baru {$pegawai->name} telah ditambahkan dan menunggu assignment company",
                    'company_id' => null, // belum ada company
                    'data' => [
                        'pegawai_id' => $pegawai->id,
                        'pegawai_nama' => $pegawai->name,
                    ]
                ],
                [
                    // Hanya superadmin
                    [
                        'role' => 'superadmin',
                    ],
                ]
            );
        });

        // Saat pegawai di-UPDATE (assign company) notif ke ADMIN
        static::updated(function ($pegawai) {
            // Cek apakah company_id berubah dari NULL ke ada nilai
            if ($pegawai->isDirty('company_id') && $pegawai->company_id) {
                $oldCompanyId = $pegawai->getOriginal('company_id');
                
                // Hanya kirim notif jika sebelumnya NULL (assignment pertama kali)
                // atau pindah company
                if ($oldCompanyId !== $pegawai->company_id) {
                    NotificationService::create(
                        [
                            'type' => 'new_employee_assigned',
                            'title' => 'Pegawai Baru Ditambahkan',
                            'message' => "Terdapat pegawai baru yang ditambahkan di company Anda: {$pegawai->name}",
                            'company_id' => $pegawai->company_id,
                            'data' => [
                                'pegawai_id' => $pegawai->id,
                                'pegawai_nama' => $pegawai->name,
                                'assigned_from_null' => $oldCompanyId === null,
                            ]
                        ],
                        [
                            // Hanya admin dari company yang bersangkutan
                            [
                                'role' => 'admin',
                                'company_id' => $pegawai->company_id,
                            ],
                        ]
                    );
                }
            }
        });
    }
}
