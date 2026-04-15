<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Scanner\ScanController;
use App\Http\Controllers\Scanner\RiwayatController as ScannerRiwayatController;
use App\Http\Controllers\Siswa\RiwayatController as SiswaRiwayatController;
use App\Http\Controllers\Siswa\ProfilController as SiswaProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publik
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:administrator'])->get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
Route::middleware(['auth', 'role:scanner'])->get('/dashboard/scanner', [DashboardController::class, 'scanner'])->name('dashboard.scanner');
Route::middleware(['auth', 'role:siswa'])->get('/dashboard/siswa', [DashboardController::class, 'siswa'])->name('dashboard.siswa');

/*
|--------------------------------------------------------------------------
| Administrator
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('jurusan', JurusanController::class)->except('show');
    Route::resource('kelas', KelasController::class)->except('show')->parameters(['kelas' => 'kela']);
    Route::resource('siswa', SiswaController::class)->except('show');
    Route::get('barcode', [BarcodeController::class, 'index'])->name('barcode.index');
    Route::post('barcode/generate-all', [BarcodeController::class, 'generateAll'])->name('barcode.generate-all');
    Route::post('barcode/{siswa}/generate', [BarcodeController::class, 'generateSingle'])->name('barcode.generate');
    Route::get('barcode/print', [BarcodeController::class, 'print'])->name('barcode.print');
    Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('rekap/detail', [RekapController::class, 'detail'])->name('rekap.detail');
    Route::resource('pengaturan', PengaturanController::class)->except('show')->parameters(['pengaturan' => 'user']);
});

/*
|--------------------------------------------------------------------------
| Scanner
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:scanner'])->prefix('scanner')->name('scanner.')->group(function () {
    Route::get('riwayat', [ScannerRiwayatController::class, 'index'])->name('riwayat.index');
    Route::post('process', [ScanController::class, 'process'])->name('process');
});

/*
|--------------------------------------------------------------------------
| Siswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('riwayat', [SiswaRiwayatController::class, 'index'])->name('siswa.riwayat.index');
    Route::get('profil', [SiswaProfilController::class, 'index'])->name('siswa.profil.index');
    Route::put('profil', [SiswaProfilController::class, 'update'])->name('siswa.profil.update');
});
