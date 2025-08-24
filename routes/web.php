<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveiController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\JenisBantuanController;
use App\Http\Controllers\AnggotaKeluargaController;
use App\Http\Controllers\PenerimaBantuanController;
use App\Http\Controllers\PenyaluranBantuanController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('desas', DesaController::class); 
    Route::resource('jenis_bantuans', JenisBantuanController::class);
    Route::resource('keluargas', KeluargaController::class);
    Route::resource('keluargas.anggota-keluargas', AnggotaKeluargaController::class)->except(['index', 'show']);
    Route::resource('surveis', SurveiController::class)->only(['index', 'edit', 'update']);
    Route::resource('penerima-bantuan', PenerimaBantuanController::class);
    Route::resource('penyaluran-bantuan', PenyaluranBantuanController::class);

    //report routes
    Route::get('/reports/keluarga', [ReportController::class, 'showKeluargaReport'])->name('reports.keluarga.index');
    Route::get('/reports/keluarga/print', [ReportController::class, 'printKeluargaReport'])->name('reports.keluarga.print');
    Route::get('/reports/survey', [ReportController::class, 'showSurveyReport'])->name('reports.survey.index');
    Route::get('/reports/survey/print', [ReportController::class, 'printSurveyReport'])->name('reports.survey.print');
    Route::get('/reports/penerima-bantuan', [ReportController::class, 'showPenerimaBantuanReport'])->name('reports.penerima_bantuan.index');
    Route::get('/reports/penerima-bantuan/print', [ReportController::class, 'printPenerimaBantuanReport'])->name('reports.penerima_bantuan.print');
    Route::get('/reports/penyaluran-bantuan', [ReportController::class, 'showPenyaluranBantuanReport'])->name('reports.penyaluran_bantuan.index');
    Route::get('/reports/penyaluran-bantuan/print', [ReportController::class, 'printPenyaluranBantuanReport'])->name('reports.penyaluran_bantuan.print');
    Route::get('/reports/total-penyaluran-per-desa', [ReportController::class, 'showTotalPenyaluranPerDesaReport'])->name('reports.total_penyaluran_per_desa.index');
    Route::get('/reports/total-penyaluran-per-desa/print', [ReportController::class, 'printTotalPenyaluranPerDesaReport'])->name('reports.total_penyaluran_per_desa.print');
    Route::get('/reports/jadwal-penyaluran', [ReportController::class, 'showJadwalPenyaluranReport'])->name('reports.jadwal_penyaluran.index');
    Route::get('/reports/jadwal-penyaluran/print', [ReportController::class, 'printJadwalPenyaluranReport'])->name('reports.jadwal_penyaluran.print');
    Route::get('/reports/analisis-nominal', [ReportController::class, 'showAnalisisNominalReport'])->name('reports.analisis_nominal.index');
    Route::get('/reports/analisis-nominal/print', [ReportController::class, 'printAnalisisNominalReport'])->name('reports.analisis_nominal.print');
    Route::get('/reports/detail-data-keluarga', [ReportController::class, 'showDetailDataKeluargaReport'])->name('reports.detail_data_keluarga.index');
    Route::get('/reports/detail-data-keluarga/print', [ReportController::class, 'printDetailDataKeluargaReport'])->name('reports.detail_data_keluarga.print');
});


