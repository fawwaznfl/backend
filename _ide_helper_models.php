<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property int|null $company_id
 * @property int|null $lokasi_id
 * @property int|null $shift_id
 * @property string $tanggal
 * @property string|null $jam_masuk
 * @property int|null $telat
 * @property string|null $lokasi_masuk
 * @property string|null $jam_pulang
 * @property int|null $pulang_cepat
 * @property string|null $lokasi_pulang
 * @property string|null $foto_pulang
 * @property string|null $keterangan_pulang
 * @property string|null $foto_masuk
 * @property string|null $keterangan_masuk
 * @property string|null $keterangan
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $status
 * @property string|null $verifikasi
 * @property int|null $approved_by
 * @property string|null $face_score
 * @property int $face_verified
 * @property-read \App\Models\Pegawai|null $approver
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Lokasi|null $lokasi
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\Shift|null $shift
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereFaceScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereFaceVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereFotoMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereFotoPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereKeteranganMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereKeteranganPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereLokasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereLokasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereLokasiPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi wherePulangCepat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereTelat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereVerifikasi($value)
 */
	class Absensi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $tipe
 * @property string $judul
 * @property string|null $isi_konten
 * @property string|null $gambar
 * @property string|null $tanggal_publikasi
 * @property string|null $upload_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereIsiKonten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereTanggalPublikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Berita whereUploadFile($value)
 */
	class Berita extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $website
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kontrak> $kontraks
 * @property-read int|null $kontraks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawais
 * @property-read int|null $pegawais_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereWebsite($value)
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $pegawai_id
 * @property string $jenis_cuti
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string|null $alasan
 * @property string|null $foto
 * @property string $status
 * @property int|null $disetujui_oleh
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $catatan
 * @property-read \App\Models\Pegawai|null $approvedBy
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereAlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereDisetujuiOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereJenisCuti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereUpdatedBy($value)
 */
	class Cuti extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\TargetKinerja|null $target
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTarget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTarget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTarget query()
 */
	class DetailTarget extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $pegawai_id
 * @property int|null $lokasi_id
 * @property int|null $shift_id
 * @property string|null $tujuan
 * @property string|null $keterangan
 * @property string $tanggal
 * @property string $status
 * @property string $verifikasi
 * @property int|null $approved_by
 * @property string|null $verified_at
 * @property string|null $jam_masuk
 * @property string|null $telat
 * @property string|null $lokasi_masuk
 * @property string|null $foto_masuk
 * @property string|null $jam_pulang
 * @property string|null $pulang_cepat
 * @property string|null $foto_pulang
 * @property string|null $lokasi_pulang
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pegawai|null $approver
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Lokasi|null $lokasi
 * @property-read \App\Models\Pegawai|null $pegawai
 * @property-read \App\Models\Shift|null $shift
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereFotoMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereFotoPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereLokasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereLokasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereLokasiPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar wherePulangCepat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereTelat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuar whereVerifikasi($value)
 */
	class DinasLuar extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int $pegawai_id
 * @property int $shift_id
 * @property string $tanggal_mulai
 * @property string|null $tanggal_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\Shift $shift
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DinasLuarMapping whereUpdatedAt($value)
 */
	class DinasLuarMapping extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $nama
 * @property string|null $deskripsi
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawais
 * @property-read int|null $pegawais_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Divisi whereUpdatedBy($value)
 */
	class Divisi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property int $company_id
 * @property string $nama_dokumen
 * @property string $file
 * @property string|null $keterangan
 * @property string|null $tanggal_upload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read mixed $file_url
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereNamaDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereTanggalUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenPegawai whereUpdatedAt($value)
 */
	class DokumenPegawai extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property string $embedding
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding whereEmbedding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaceEmbedding whereUpdatedAt($value)
 */
	class FaceEmbedding extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $lokasi_id
 * @property int|null $divisi_id
 * @property string $kode_barang
 * @property string $nama_barang
 * @property int $stok
 * @property string|null $satuan
 * @property string|null $tanggal_masuk
 * @property string $status
 * @property string|null $keterangan
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Divisi|null $divisi
 * @property-read \App\Models\Lokasi|null $lokasi
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereDivisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereKodeBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereLokasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereNamaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereTanggalMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventory whereUpdatedBy($value)
 */
	class Inventory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $nama
 * @property string|null $detail
 * @property string $bobot_penilaian
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereBobotPenilaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKinerja whereUpdatedBy($value)
 */
	class JenisKinerja extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int $pegawai_id
 * @property string $tanggal
 * @property string $nominal
 * @property string|null $keperluan
 * @property string $metode_pengiriman
 * @property string|null $nomor_rekening
 * @property string $status
 * @property string|null $file_approve
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $disetujui_oleh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereDisetujuiOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereFileApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereKeperluan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereMetodePengiriman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kasbon whereUpdatedBy($value)
 */
	class Kasbon extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $nama
 * @property string|null $jumlah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KategoriReimbursement whereUpdatedAt($value)
 */
	class KategoriReimbursement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $pegawai_id
 * @property string|null $nomor_kontrak
 * @property string $jenis_kontrak
 * @property string|null $tanggal_mulai
 * @property string|null $tanggal_selesai
 * @property string|null $file_kontrak
 * @property int $notified_h30
 * @property int $notified_h7
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $keterangan
 * @property-read \App\Models\Company|null $company
 * @property-read mixed $file_url
 * @property-read \App\Models\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereFileKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereJenisKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereNomorKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereNotifiedH30($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereNotifiedH7($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kontrak whereUpdatedBy($value)
 */
	class Kontrak extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $pegawai_id
 * @property string|null $upload_foto
 * @property string|null $lokasi_masuk
 * @property string|null $foto_keluar
 * @property string|null $lokasi_keluar
 * @property string|null $keterangan
 * @property string|null $keterangan_keluar
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereFotoKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereKeteranganKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereLokasiKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereLokasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kunjungan whereUploadFoto($value)
 */
	class Kunjungan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property string $informasi_umum
 * @property string $pekerjaan_yang_dilaksanakan
 * @property string $pekerjaan_belum_selesai
 * @property string $catatan
 * @property string $tanggal_laporan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja whereInformasiUmum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja wherePekerjaanBelumSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja wherePekerjaanYangDilaksanakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja whereTanggalLaporan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaporanKerja whereUpdatedAt($value)
 */
	class LaporanKerja extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $pegawai_id
 * @property string $tanggal_lembur
 * @property string|null $lokasi_masuk
 * @property string|null $foto_masuk
 * @property string|null $jam_mulai
 * @property string|null $jam_selesai
 * @property int|null $total_lembur_menit
 * @property string|null $lokasi_pulang
 * @property string|null $keterangan
 * @property string|null $foto_pulang
 * @property string $status
 * @property int|null $disetujui_oleh
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $creator
 * @property-read mixed $foto_masuk_url
 * @property-read mixed $foto_pulang_url
 * @property-read \App\Models\Lokasi|null $lokasi
 * @property-read \App\Models\Pegawai|null $pegawai
 * @property-read \App\Models\Shift|null $shift
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereDisetujuiOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereFotoMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereFotoPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereLokasiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereLokasiPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereTanggalLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereTotalLemburMenit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lembur whereUpdatedBy($value)
 */
	class Lembur extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $nama_lokasi
 * @property string|null $lat_kantor
 * @property string|null $long_kantor
 * @property int $radius
 * @property string|null $keterangan
 * @property string $status
 * @property int|null $created_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLatKantor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereLongKantor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereNamaLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereRadius($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lokasi whereUpdatedAt($value)
 */
	class Lokasi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string $message
 * @property array<array-key, mixed>|null $data
 * @property int|null $company_id
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NotificationTarget> $targets
 * @property-read int|null $targets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $notification_id
 * @property int|null $user_id
 * @property string|null $role
 * @property int|null $company_id
 * @property int $is_read
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Notification $notification
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereNotificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTarget whereUserId($value)
 */
	class NotificationTarget extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $updater
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi query()
 */
	class Notifikasi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int $pegawai_id
 * @property string|null $lokasi
 * @property string $tujuan
 * @property string|null $keterangan
 * @property string|null $tanggal_mulai
 * @property string|null $tanggal_selesai
 * @property string|null $bukti_patrol
 * @property string $status
 * @property string|null $catatan
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereBuktiPatrol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patroli whereUpdatedBy($value)
 */
	class Patroli extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int $pegawai_id
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $divisi_id
 * @property string|null $rekening
 * @property string|null $tanggal_gabung
 * @property string|null $bulan
 * @property int|null $tahun
 * @property string|null $periode
 * @property string|null $nomor_gaji
 * @property string $gaji_pokok
 * @property string $uang_transport
 * @property string $reimbursement
 * @property int $lembur
 * @property string $uang_lembur
 * @property string $bonus_pribadi
 * @property string $bonus_team
 * @property string $bonus_jackpot
 * @property int $kehadiran_100
 * @property string $bonus_kehadiran
 * @property string $tunjangan_bpjs_kesehatan
 * @property string $tunjangan_bpjs_ketenagakerjaan
 * @property string $tunjangan_pajak
 * @property int $thr
 * @property string $tunjangan_hari_raya
 * @property string $total_tambah
 * @property int $mangkir
 * @property string $uang_mangkir
 * @property int $izin
 * @property string $uang_izin
 * @property int $terlambat
 * @property string $uang_terlambat
 * @property string $bayar_kasbon
 * @property string $potongan_bpjs_kesehatan
 * @property string $potongan_bpjs_ketenagakerjaan
 * @property string $loss
 * @property string $total_pengurangan
 * @property string $gaji_diterima
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\RekapPajakPegawai|null $rekapPajak
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBayarKasbon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBonusJackpot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBonusKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBonusPribadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBonusTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereDivisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereGajiDiterima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereGajiPokok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereKehadiran100($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereLoss($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereMangkir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereNomorGaji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll wherePotonganBpjsKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll wherePotonganBpjsKetenagakerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereReimbursement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTanggalGabung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereThr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTotalPengurangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTotalTambah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTunjanganBpjsKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTunjanganBpjsKetenagakerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTunjanganHariRaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTunjanganPajak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUangIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUangLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUangMangkir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUangTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUangTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUpdatedAt($value)
 */
	class Payroll extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $role_id
 * @property int|null $divisi_id
 * @property int|null $lokasi_id
 * @property int|null $shift_id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string|null $telepon
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property string|null $foto_karyawan
 * @property string|null $foto_face_recognition
 * @property \Illuminate\Support\Carbon|null $tgl_lahir
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $tgl_join
 * @property string|null $status_nikah
 * @property string|null $alamat
 * @property string|null $ktp
 * @property string|null $kartu_keluarga
 * @property string|null $bpjs_kesehatan
 * @property string|null $bpjs_ketenagakerjaan
 * @property string|null $npwp
 * @property string|null $sim
 * @property string|null $no_pkwt
 * @property string|null $no_kontrak
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai_pwkt
 * @property \Illuminate\Support\Carbon|null $tanggal_berakhir_pwkt
 * @property \Illuminate\Support\Carbon|null $masa_berlaku
 * @property string|null $rekening
 * @property string|null $nama_rekening
 * @property string $gaji_pokok
 * @property string $makan_transport
 * @property string $lembur
 * @property string $kehadiran
 * @property string $thr
 * @property string $bonus_pribadi
 * @property string $bonus_team
 * @property string $bonus_jackpot
 * @property string $izin
 * @property string $terlambat
 * @property int $mangkir
 * @property string $saldo_kasbon
 * @property string|null $status_pajak
 * @property string $tunjangan_bpjs_kesehatan
 * @property string $potongan_bpjs_kesehatan
 * @property string $tunjangan_bpjs_ketenagakerjaan
 * @property string $potongan_bpjs_ketenagakerjaan
 * @property string $tunjangan_pajak
 * @property int $izin_cuti
 * @property int $izin_lainnya
 * @property int $izin_telat
 * @property int $izin_pulang_cepat
 * @property string $status
 * @property string $dashboard_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Absensi> $absensis
 * @property-read int|null $absensis_count
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Divisi|null $divisi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PegawaiFace> $faces
 * @property-read int|null $faces_count
 * @property-read mixed $foto_karyawan_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lembur> $lemburs
 * @property-read int|null $lemburs_count
 * @property-read \App\Models\Lokasi|null $lokasi
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Pegawai|null $pegawai
 * @property-read \App\Models\PegawaiKeluar|null $pegawaiKeluar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rapat> $rapats
 * @property-read int|null $rapats_count
 * @property-read \App\Models\Role|null $role
 * @property-read \App\Models\Shift|null $shift
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBonusJackpot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBonusPribadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBonusTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBpjsKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBpjsKetenagakerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDashboardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDivisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereFotoFaceRecognition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereFotoKaryawan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGajiPokok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereIzinCuti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereIzinLainnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereIzinPulangCepat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereIzinTelat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKartuKeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereLokasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereMakanTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereMangkir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereMasaBerlaku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNamaRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNoKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNoPkwt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePotonganBpjsKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePotonganBpjsKetenagakerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSaldoKasbon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereStatusNikah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereStatusPajak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTanggalBerakhirPwkt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTanggalMulaiPwkt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTglJoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereThr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTunjanganBpjsKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTunjanganBpjsKetenagakerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTunjanganPajak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUsername($value)
 */
	class Pegawai extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property array<array-key, mixed> $embedding
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace whereEmbedding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiFace whereUpdatedAt($value)
 */
	class PegawaiFace extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int|null $pegawai_id
 * @property string|null $jenis_keberhentian
 * @property string|null $alasan
 * @property string|null $tanggal_keluar
 * @property string|null $upload_file
 * @property string $status
 * @property string|null $note_approver
 * @property int|null $disetujui_oleh
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read mixed $upload_file_url
 * @property-read \App\Models\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereAlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereDisetujuiOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereJenisKeberhentian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereNoteApprover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereTanggalKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiKeluar whereUploadFile($value)
 */
	class PegawaiKeluar extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $nomor_penugasan
 * @property int|null $company_id
 * @property int|null $pegawai_id
 * @property string $judul_pekerjaan
 * @property string|null $rincian_pekerjaan
 * @property string $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Pegawai|null $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereJudulPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereNomorPenugasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereRincianPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penugasan whereUpdatedBy($value)
 */
	class Penugasan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $nama_pertemuan
 * @property string|null $detail_pertemuan
 * @property \Illuminate\Support\Carbon|null $tanggal_rapat
 * @property string|null $waktu_mulai
 * @property string|null $waktu_selesai
 * @property string $jenis_pertemuan
 * @property string|null $lokasi
 * @property string|null $file_notulen
 * @property string|null $notulen
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai|null $pegawai
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pegawai> $pegawais
 * @property-read int|null $pegawais_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereDetailPertemuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereFileNotulen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereJenisPertemuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereNamaPertemuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereNotulen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereTanggalRapat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rapat whereWaktuSelesai($value)
 */
	class Rapat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int $pegawai_id
 * @property int $kategori_reimbursement_id
 * @property string $tanggal
 * @property string|null $event
 * @property string $metode_reim
 * @property string|null $no_rekening
 * @property int|null $jumlah
 * @property int $terpakai
 * @property string|null $total
 * @property int|null $sisa
 * @property string $status
 * @property string|null $file
 * @property string|null $approved_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\KategoriReimbursement $kategori
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereApprovedFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereKategoriReimbursementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereMetodeReim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereNoRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereSisa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereTerpakai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reimbursement whereUpdatedAt($value)
 */
	class Reimbursement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int $pegawai_id
 * @property int|null $payroll_id
 * @property int|null $tarif_pph_id
 * @property string|null $bulan
 * @property string|null $tahun
 * @property string $penghasilan_bruto
 * @property string $penghasilan_netto
 * @property string $ptkp Penghasilan Tidak Kena Pajak
 * @property string $pkp Penghasilan Kena Pajak
 * @property string $tarif_persen
 * @property string $pajak_terutang
 * @property string $pajak_dipotong
 * @property string $pajak_selisih Selisih antara pajak terutang dan dipotong
 * @property string|null $tanggal_proses
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Payroll|null $payroll
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\TarifPph|null $tarifPph
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePajakDipotong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePajakSelisih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePajakTerutang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePayrollId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePenghasilanBruto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePenghasilanNetto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePkp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai wherePtkp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereTanggalProses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereTarifPersen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereTarifPphId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPajakPegawai whereUpdatedAt($value)
 */
	class RekapPajakPegawai extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int $pegawai_id
 * @property string $tipe
 * @property int $sumber_id
 * @property string $tanggal_pengajuan
 * @property string|null $keterangan
 * @property string $nominal
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $sumber
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereSumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereTanggalPengajuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RekapPengajuanKeuangan whereUpdatedAt($value)
 */
	class RekapPengajuanKeuangan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $nama
 * @property string|null $deskripsi
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedBy($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property string $nama
 * @property string|null $jam_masuk
 * @property string|null $jam_pulang
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUpdatedBy($value)
 */
	class Shift extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $company_id
 * @property int $pegawai_id
 * @property int $shift_id
 * @property int|null $shift_lama_id
 * @property int $toleransi_telat
 * @property string|null $status_toleransi
 * @property string $tanggal_mulai
 * @property string|null $tanggal_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $status
 * @property int|null $requested_by
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Absensi> $absensi
 * @property-read int|null $absensi_count
 * @property-read \App\Models\Company|null $company
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\Shift $shift
 * @property-read \App\Models\Shift|null $shiftLama
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereShiftLamaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereStatusToleransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereToleransiTelat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShiftMapping whereUpdatedAt($value)
 */
	class ShiftMapping extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property string $nomor_target
 * @property int $pegawai_id
 * @property int|null $divisi_id
 * @property string $target_pribadi
 * @property string $jumlah_persen_pribadi
 * @property string $bonus_pribadi
 * @property string $target_team
 * @property string $jumlah_persen_team
 * @property string $bonus_team
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $jackpot
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Divisi|null $divisi
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereBonusPribadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereBonusTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereDivisiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereJackpot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereJumlahPersenPribadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereJumlahPersenTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereNomorTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereTargetPribadi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereTargetTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TargetKinerja whereUpdatedAt($value)
 */
	class TargetKinerja extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TarifPph newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TarifPph newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TarifPph query()
 */
	class TarifPph extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Pegawai|null $pegawai
 * @property-read \App\Models\Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 */
	class User extends \Eloquent {}
}

