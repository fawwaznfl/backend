<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


// Controllers
use App\Http\Controllers\API\Attendance\{
    AbsensiController, CutiController, DinasLuarController, DinasLuarMappingController, KunjunganController, LemburController, PatroliController,
    ShiftMappingController
};
use App\Http\Controllers\Api\AuthPegawaiController;
use App\Http\Controllers\API\Core\{
    RoleController, DivisiController, PegawaiController, LokasiController, ShiftController
};
use App\Http\Controllers\Api\Dashboard\BirthdayCalendarController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Dashboard\SakitCalendarController;
use App\Http\Controllers\API\FaceController;
use App\Http\Controllers\API\HR\{
    KontrakController, PegawaiKeluarController, DokumenPegawaiController
};
use App\Http\Controllers\API\Performance\{
    JenisKinerjaController, TargetKinerjaController
};
use App\Http\Controllers\API\Info\{
    NotifikasiController, PenugasanController, RapatController, BeritaController,
    NotificationController
};
use App\Http\Controllers\API\Inventory\InventoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\API\Finance\KasbonController;
use App\Http\Controllers\API\Finance\KategoriReimbursementController;
use App\Http\Controllers\API\Finance\ReimbursementController;
use App\Http\Controllers\API\Finance\RekapPengajuanKeuanganController;
use App\Http\Controllers\Api\Finance\TarifPphController;
use App\Http\Controllers\Api\Finance\PayrollController;
use App\Http\Controllers\Api\Finance\RekapAbsensiController;
use App\Http\Controllers\Api\Finance\RekapPajakPegawaiController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RekapData\LaporanKerjaController;
use App\Http\Controllers\Api\RekapData\RekapAbsensiSummaryController;

/*
|--------------------------------------------------------------------------
| API Routes v1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    //  AUTH ROUTES
    Route::post('login', [AuthPegawaiController::class, 'login']);
    Route::post('register', [AuthPegawaiController::class, 'register']);
    Route::get('/v1/public/companies', [CompanyController::class, 'publicList']);
    Route::post('logout', [AuthPegawaiController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->post(
        '/change-password',
        [\App\Http\Controllers\Api\ProfileController::class, 'changePassword']
    );

    Route::middleware('auth:sanctum')->get(
        '/profile',
        [\App\Http\Controllers\Api\ProfileController::class, 'profile']
    );

    Route::post('/forgot-password', [ForgotPasswordController::class, 'verifyEmail']);  
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

    Route::get('/shifts/company/{id}', function ($id) {
            return \App\Models\Shift::where('company_id', $id)->get();
        });
    
    // =======================
    //  PEGAWAI DASHBOARD
    // =======================
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('shift-mapping/self/{pegawai_id}', [ShiftMappingController::class, 'showByPegawai']);
        Route::get('/shift-mapping/by-pegawai/{pegawai_id}', [ShiftMappingController::class, 'showByPegawai']);
        Route::get('shift-mapping/today/{pegawai_id}', [ShiftMappingController::class, 'today']);
        Route::post('shift-mapping/request/{id}', [ShiftMappingController::class, 'requestUpdate']);
        
        Route::get('kategori-reimbursement', [KategoriReimbursementController::class, 'index']);   
        Route::get('kategori-reimbursement/{id}', [KategoriReimbursementController::class, 'show']);
        Route::apiResource('shifts', ShiftController::class); 

        // Data pribadi & absensi
        Route::get('my-profile', [PegawaiController::class, 'myProfile']);
        Route::get('/my-profile', [AuthPegawaiController::class, 'myProfile']);

        // absensi
        Route::get('/absensi/self', [AbsensiController::class, 'self']);
        Route::post('/absensi/masuk', [AbsensiController::class, 'absenMasuk']);
        Route::post('/absensi/pulang', [AbsensiController::class, 'absenPulang']);
        Route::get('/absensi/check/{pegawai_id}', [AbsensiController::class, 'checkToday']);
        Route::get('absensi/status-pegawai/{id}', [AbsensiController::class, 'statusPegawai']);
        Route::apiResource('absensi', AbsensiController::class);
        Route::post('absensi/auto', [AbsensiController::class, 'autoAbsen']);
        Route::get('/absensi/by-pegawai/{pegawai}', [AbsensiController::class, 'byPegawai']);
        Route::get('/absensi/aktif/{pegawai}', [AbsensiController::class, 'absensiAktif']);
        Route::get('/shift-mapping/by-date/{pegawai}', [ShiftMappingController::class, 'byDate']);

        //face recognition
        Route::post('/face/register', [FaceController::class, 'register']);
        Route::post('/face/verify', [FaceController::class, 'verifyViaBackend']);
        Route::get('/face/check/{pegawai_id}', [FaceController::class, 'check']);

        //payroll
        Route::get('/penggajian', [PayrollController::class, 'index']);
        Route::get('/penggajian/{id}/pdf', [PayrollController::class, 'downloadPdf']);
        Route::get('/penggajian/{id}/pdf-slip', [PayrollController::class, 'pdfSlip']);

        //Notifikasi
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'read']);
        Route::post('/notifications/mark-all-personal-read', [NotificationController::class, 'markAllPersonalAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

        //Dinas Luar
        Route::get('dinas-luar-mapping/self/{pegawai_id}', [DinasLuarMappingController::class, 'showByPegawai']);
        Route::get('/dinas-luar-mapping/by-pegawai/{pegawai_id}', [DinasLuarMappingController::class, 'showByPegawai']);
        Route::get('dinas-luar-mapping/today/{pegawai_id}', [DinasLuarMappingController::class, 'today']);

        //Lembur
        Route::post('lembur/auto', [LemburController::class, 'autoAbsen']);
        Route::get('/lembur/by-pegawai/{pegawai}', [LemburController::class, 'byPegawai']);

        //Rekap Data
        Route::get('/rekap-absensi/export', [RekapAbsensiSummaryController::class,'exportExcel']);
        Route::get('/rekap-absensi/export-pdf', [RekapAbsensiSummaryController::class,'exportPdf']);
        //Rekap data 2
        Route::get('/rekap-absensi/export-summary', [RekapAbsensiSummaryController::class,'exportRekap']);
        Route::get('/rekap-absensi/export-rekap-pdf', [RekapAbsensiSummaryController::class,'exportRekapPdf']);

        // laporan kerja
        Route::apiResource('laporan-kerja', LaporanKerjaController::class);
        Route::get('/laporan-kerja/by-pegawai/{pegawai_id}',[LaporanKerjaController::class, 'byPegawai']);
        Route::get('laporan-kerja/me', [LaporanKerjaController::class, 'myLaporan']);

        //Kunjungan
        Route::apiResource('kunjungan', KunjunganController::class); 

        // Request Shift
        Route::post('shift-mapping/request-new', [ShiftMappingController::class, 'requestNew']);

        //rapat
        Route::apiResource('rapat', RapatController::class); 
        Route::post('/rapat/{id}/hadir', [RapatController::class, 'hadir']);
        Route::post('rapat/{id}/notulen', [RapatController::class, 'simpanNotulen']);

        //Dokumen
        Route::apiResource('dokumen-pegawai', DokumenPegawaiController::class); 
        Route::get('/dokumen-pegawai/by-pegawai/{pegawai}', [DokumenPegawaiController::class, 'byPegawai']);

        //Penugasan
        Route::apiResource('penugasan', PenugasanController::class); 

        //Berita
        Route::apiResource('berita', BeritaController::class); 

        //Profile
        Route::post('/profile/update-foto', [ProfileController::class, 'updateFoto']);
        Route::post('/profile/update', [ProfileController::class, 'update']);
        
        //hitung cuti
        Route::get('/cuti/summary/{pegawai_id}', [CutiController::class, 'summary']);

        //Inventory
        Route::apiResource('inventory', InventoryController::class); 
        Route::get('/pegawai/divisi', [DivisiController::class, 'divisiPegawai']);

        Route::get('/dinas-luar/self', [DinasLuarController::class, 'self']);
        Route::post('/dinas-luar/masuk', [DinasLuarController::class, 'absenMasuk']);
        Route::post('/dinas-luar/pulang', [DinasLuarController::class, 'absenPulang']);
        Route::get('/dinas-luar/check/{pegawai_id}', [DinasLuarController::class, 'checkToday']);
        Route::get('dinas-luar/status-pegawai/{id}', [DinasLuarController::class, 'statusPegawai']);
        Route::post('dinas-luar/auto', [DinasLuarController::class, 'autoAbsen']);
        Route::get('/dinas-luar/by-pegawai/{pegawai}', [DinasLuarController::class, 'byPegawai']);

        Route::apiResource('cuti', CutiController::class); 
        Route::get('/cuti/check-today/{pegawai_id}', [CutiController::class, 'checkToday']);
        Route::apiResource('lembur', LemburController::class)->only(['index', 'store', 'show']); 
        
        // Notifikasi dan info umum
        Route::apiResource('notifikasi', NotifikasiController::class)->only(['index', 'show']); 
        Route::apiResource('berita', BeritaController::class)->only(['index', 'show']); 

        // Kategori Reimbursement 
        Route::apiResource('kategori-reimbursement', KategoriReimbursementController::class);
        Route::get('/kasbon/sisa', [KasbonController::class, 'sisaKasbon']);
        Route::apiResource('kasbon', KasbonController::class);

        // Reimbursement
        Route::apiResource('reimbursement', ReimbursementController::class);

        //kasbon
        Route::post('/kasbon/{id}/bayar', [KasbonController::class, 'bayar']);
    });

    // ========================
    //  SUPERADMIN DASHBOARD
    // ========================
    Route::middleware(['auth:sanctum', 'role.dashboard:superadmin', 'company.scope'])->group(function () {

        // Core 
        Route::apiResource('companies', CompanyController::class); 
        Route::apiResource('roles', RoleController::class); 
        Route::apiResource('divisis', DivisiController::class); 
        Route::apiResource('pegawais', PegawaiController::class); 
        Route::apiResource('lokasis', LokasiController::class); 

        // HR 
        Route::apiResource('kontrak', KontrakController::class); 
        Route::apiResource('pegawai-keluar', PegawaiKeluarController::class); 
        Route::post('pegawai-keluar/{id}/approve', [PegawaiKeluarController::class, 'approve']);
        Route::post('pegawai-keluar/{id}/reject', [PegawaiKeluarController::class, 'reject']);
        Route::put('/pegawai-keluar/{id}/approve', [PegawaiKeluarController::class, 'approve']);

        // Attendance
        Route::apiResource('absensi', AbsensiController::class); 
        Route::put('/cuti/{id}/approve', [CutiController::class, 'approve']);
        Route::put('cuti/{id}/reject', [CutiController::class, 'reject']);
        Route::apiResource('dinas-luar', DinasLuarController::class); 
        Route::apiResource('lembur', LemburController::class); 
        Route::apiResource('patroli', PatroliController::class); 
        Route::get('shift-mapping/requests', [ShiftMappingController::class, 'requests']);
        Route::get('shift-mapping/pegawai/{id}', [ShiftMappingController::class, 'byPegawai']);
        Route::post('shift-mapping/{id}/approve', [ShiftMappingController::class, 'approve']);
        Route::post('shift-mapping/{id}/reject', [ShiftMappingController::class, 'reject']);

        // API RESOURCE
        Route::apiResource('shift-mapping', ShiftMappingController::class);
        Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy']);

        Route::post('lembur/{id}/approve', [LemburController::class, 'approve']);
        Route::post('lembur/{id}/reject', [LemburController::class, 'reject']);
        Route::get('/rekap-absensi/summary', [RekapAbsensiSummaryController::class, 'index']);

        Route::apiResource('dinas-luar-mapping', DinasLuarMappingController::class);
        Route::put('dinas-luar-mapping/{id}', [DinasLuarMappingController::class, 'update']);
        Route::get('dinas-luar-mapping/pegawai/{id}', [DinasLuarMappingController::class, 'byPegawai']);

        //Payroll
        Route::get('/payrolls/{id}/download', [PayrollController::class, 'download']);
        Route::get('/rekap-absensi/pegawai/{pegawaiId}',[RekapAbsensiController::class, 'rekapPegawai']);
        Route::get('/kasbon/total-approve/{pegawai}', [KasbonController::class, 'totalKasbonApprove']);


        //Notifikasi
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);

        // Profile
        Route::get('/user/profile', [PegawaiController::class, 'getProfile']);
        Route::post('/user/profile', [PegawaiController::class, 'updateProfile']);


        // Performance 
        Route::apiResource('jenis-kinerja', JenisKinerjaController::class); 
        Route::apiResource('target-kinerja', TargetKinerjaController::class); 

        //Face Recognition
        //Route::post('/face/register', [FaceController::class, 'register']);

        //dashboard
        Route::get('/dashboard/metrics', [DashboardController::class, 'metrics']);
        Route::get('/calendar/birthdays', [BirthdayCalendarController::class, 'index']);
        Route::get('/calendar/sakit', [SakitCalendarController::class, 'index']);
        Route::get('/calendar/cuti', [SakitCalendarController::class, 'cuti']);
        Route::get('/calendar/izin', [SakitCalendarController::class, 'izin']);
        Route::get('/calendar/telat', [SakitCalendarController::class, 'telat']);
        Route::get('/calendar/pulang', [SakitCalendarController::class, 'pulang']);
        Route::apiResource('notifikasi', NotifikasiController::class); 

        // Keuangan 
        Route::patch('/kasbon/{id}/approve', [KasbonController::class, 'approve']);
        Route::patch('/kasbon/{id}/reject', [KasbonController::class, 'reject']);
        Route::post('/kasbon/{id}/approval', [KasbonController::class, 'approval']);
        
        Route::apiResource('rekap-pengajuan-keuangan', RekapPengajuanKeuanganController::class);
        Route::apiResource('tarif-pph', TarifPphController::class);
        Route::apiResource('rekap-pajak-pegawai', RekapPajakPegawaiController::class);
        Route::apiResource('payrolls', PayrollController::class);
        Route::put('reimbursement/{id}/reject', [ReimbursementController::class, 'reject']);
        Route::post('reimbursement/{id}/approve', [ReimbursementController::class, 'approve']);
    });

    // ========================
    //  ADMIN DASHBOARD
    // ========================
    Route::middleware(['auth:sanctum', 'role.dashboard:admin', 'company.scope'])->group(function () {

        Route::apiResource('roles', RoleController::class); 
        Route::apiResource('divisis', DivisiController::class); 
        Route::apiResource('pegawais', PegawaiController::class); 
        Route::apiResource('lokasis', LokasiController::class); 
        Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy']);
        Route::apiResource('companies', CompanyController::class);


        // HR 
        Route::apiResource('kontrak', KontrakController::class); 
        Route::apiResource('pegawai-keluar', PegawaiKeluarController::class); 
        Route::post('pegawai-keluar/{id}/approve', [PegawaiKeluarController::class, 'approve']);
        Route::post('pegawai-keluar/{id}/reject', [PegawaiKeluarController::class, 'reject']);
        Route::put('/pegawai-keluar/{id}/approve', [PegawaiKeluarController::class, 'approve']);

        // Attendance
        Route::apiResource('absensi', AbsensiController::class); 
        Route::apiResource('dinas-luar', DinasLuarController::class);
        Route::put('/cuti/{id}/approve', [CutiController::class, 'approve']);
        Route::put('cuti/{id}/reject', [CutiController::class, 'reject']);
        Route::apiResource('lembur', LemburController::class); 
        Route::apiResource('patroli', PatroliController::class); 

        Route::get('shift-mapping/requests', [ShiftMappingController::class, 'requests']);
        Route::get('shift-mapping/pegawai/{id}', [ShiftMappingController::class, 'byPegawai']);
        Route::post('shift-mapping/{id}/approve', [ShiftMappingController::class, 'approve']);
        Route::post('shift-mapping/{id}/reject', [ShiftMappingController::class, 'reject']);

        // Profile
        Route::get('/user/profile', [PegawaiController::class, 'getProfile']);
        Route::post('/user/profile', [PegawaiController::class, 'updateProfile']);

        //Face Recognition
        //Route::post('/face/register', [FaceController::class, 'register']);

        //Payroll
        Route::get('/payrolls/{id}/download', [PayrollController::class, 'download']);
        Route::get('/rekap-absensi/pegawai/{pegawaiId}', [RekapAbsensiController::class, 'rekapPegawai']);
        Route::get('/kasbon/total-approve/{pegawai}', [KasbonController::class, 'totalKasbonApprove']);


        // API RESOURCE
        Route::apiResource('shift-mapping', ShiftMappingController::class);
        Route::post('lembur/{id}/approve', [LemburController::class, 'approve']);
        Route::post('lembur/{id}/reject', [LemburController::class, 'reject']);
        Route::get('/rekap-absensi/summary', [RekapAbsensiSummaryController::class, 'index']);

        Route::apiResource('dinas-luar-mapping', DinasLuarMappingController::class);
        Route::put('dinas-luar-mapping/{id}', [DinasLuarMappingController::class, 'update']);
        Route::get('dinas-luar-mapping/pegawai/{id}', [DinasLuarMappingController::class, 'byPegawai']);

        // Performance 
        Route::apiResource('jenis-kinerja', JenisKinerjaController::class); 
        Route::apiResource('target-kinerja', TargetKinerjaController::class); 

        // Inventory & Info 
        Route::apiResource('notifikasi', NotifikasiController::class); 
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        

        //dashboard
        Route::get('/dashboard/metrics', [DashboardController::class, 'metrics']);
        Route::get('/calendar/birthdays', [BirthdayCalendarController::class, 'index']);
        Route::get('/calendar/sakit', [SakitCalendarController::class, 'index']);
        Route::get('/calendar/cuti', [SakitCalendarController::class, 'cuti']);
        Route::get('/calendar/izin', [SakitCalendarController::class, 'izin']);
        Route::get('/calendar/telat', [SakitCalendarController::class, 'telat']);
        Route::get('/calendar/pulang', [SakitCalendarController::class, 'pulang']);

        Route::patch('/kasbon/{id}/approve', [KasbonController::class, 'approve']);
        Route::patch('/kasbon/{id}/reject', [KasbonController::class, 'reject']);
        Route::post('/kasbon/{id}/approval', [KasbonController::class, 'approval']);        
        Route::apiResource('rekap-pengajuan-keuangan', RekapPengajuanKeuanganController::class);
        Route::apiResource('tarif-pph', TarifPphController::class);
        Route::apiResource('rekap-pajak-pegawai', RekapPajakPegawaiController::class);
        Route::apiResource('payrolls', PayrollController::class);
        Route::put('reimbursement/{id}/reject', [ReimbursementController::class, 'reject']);
        Route::post('reimbursement/{id}/approve', [ReimbursementController::class, 'approve']);
        Route::post('shift-mapping/approve/{id}', [ShiftMappingController::class, 'approve']);
        Route::post('shift-mapping/reject/{id}', [ShiftMappingController::class, 'reject']);
    });
});
