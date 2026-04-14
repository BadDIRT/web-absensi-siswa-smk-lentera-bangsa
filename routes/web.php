<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publik — Login / Logout
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Routes Dashboard — Dilindungi autentikasi + role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

    // ── Placeholder routes untuk fitur admin (akan diimplementasi nanti) ──
    // Route::resource('siswa', SiswaController::class);
    // Route::resource('kelas', KelasController::class);
    // Route::resource('jurusan', JurusanController::class);
    // Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
    // Route::get('barcode/generate', [BarcodeController::class, 'generate'])->name('barcode.generate');
    // Route::resource('pengguna', PenggunaController::class);
});

Route::middleware(['auth', 'role:scanner'])->group(function () {
    Route::get('/dashboard/scanner', [DashboardController::class, 'scanner'])->name('dashboard.scanner');

    // ── Placeholder routes untuk fitur scanner ──
    // Route::get('scan/riwayat', [ScanController::class, 'riwayat'])->name('scan.riwayat');
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard/siswa', [DashboardController::class, 'siswa'])->name('dashboard.siswa');

    // ── Placeholder routes untuk fitur siswa ──
    // Route::get('absensi/riwayat', [SiswaAbsensiController::class, 'riwayat'])->name('siswa.absensi.riwayat');
    // Route::get('profil', [SiswaProfilController::class, 'index'])->name('siswa.profil');
});

/*
|--------------------------------------------------------------------------
| Redirect root ke login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});
