<?php

use App\Http\Controllers\Api\Finance\PayrollController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/penggajian/{id}/pdf', [PayrollController::class, 'downloadPdf']);
Route::get('/penggajian/{id}/pdf-slip', [PayrollController::class, 'pdfSlip']);

