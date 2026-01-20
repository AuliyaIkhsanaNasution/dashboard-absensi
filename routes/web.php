<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\CutiController; 
use App\Http\Controllers\LemburController;
// Import Model untuk Dashboard
use App\Models\Karyawan;
use App\Models\Absensi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// 2. Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 3. Route Luar Admin
Route::get('/report', function () {
    return view('report');
})->name('report');

// 4. Admin Routes (Protected by Auth Middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // ============================================
    // Dashboard Admin (Data Real dari Database)
    // ============================================
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $today = date('Y-m-d');
        
        return view('admin.dashboard', [
            'user' => $user,
            'totalKaryawan'  => Karyawan::count(),
            'hadirHariIni'   => Absensi::where('tanggal', $today)->whereIn('status', ['Hadir', 'Terlambat'])->count(),
            'terlambat'      => Absensi::where('tanggal', $today)->where('status', 'Terlambat')->count(),
            'izin'           => Absensi::where('tanggal', $today)->whereIn('status', ['Izin', 'Sakit'])->count(),
            'absensiHariIni' => Absensi::with('karyawan')->where('tanggal', $today)->latest()->take(5)->get()
        ]);
    })->name('dashboard');
    
    // ============================================
    // Kelola Karyawan
    // ============================================
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    
    // ============================================
    // Kelola Absensi
    // ============================================
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::put('/absensi/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    
    // ============================================
    // Kelola Laporan
    // ============================================
    Route::get('/laporan', function () {
        return view('admin.laporan', [
            'totalHadir'     => Absensi::where('status', 'Hadir')->count(),
            'totalTerlambat' => Absensi::where('status', 'Terlambat')->count(),
            'totalIzin'      => Absensi::where('status', 'Izin')->count(),
            'totalSakit'     => Absensi::where('status', 'Sakit')->count(),
            'laporan'        => Absensi::with('karyawan')->latest()->get()
        ]);
    })->name('laporan');

    // ============================================
    // Pengaturan Perusahaan
    // ============================================
    Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan');
    Route::post('/perusahaan/store', [PerusahaanController::class, 'store'])->name('perusahaan.store');
    Route::put('/perusahaan/{id}', [PerusahaanController::class, 'update'])->name('perusahaan.update');
    Route::delete('/perusahaan/{id}', [PerusahaanController::class, 'destroy'])->name('perusahaan.destroy');

    // ============================================
    // Kelola Izin
    // ============================================
    Route::get('/izin', [IzinController::class, 'index'])->name('izin');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
    Route::put('/izin/{izin}', [IzinController::class, 'update'])->name('izin.update');
    Route::delete('/izin/{izin}', [IzinController::class, 'destroy'])->name('izin.destroy');

    // ============================================
    // Kelola Cuti
    // ============================================
    Route::get('/cuti', [CutiController::class, 'index'])->name('cuti');
    Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');
    Route::put('/cuti/{cuti}', [CutiController::class, 'update'])->name('cuti.update');
    Route::delete('/cuti/{cuti}', [CutiController::class, 'destroy'])->name('cuti.destroy');

    Route::patch('/cuti/{id}/status', [CutiController::class, 'updateStatus'])
    ->name('cuti.status');

    // ============================================
// Kelola Lembur
// ============================================
Route::get('/lembur', [LemburController::class, 'index'])->name('lembur');
Route::post('/lembur', [LemburController::class, 'store'])->name('lembur.store');
Route::put('/lembur/{lembur}', [LemburController::class, 'update'])->name('lembur.update');
Route::delete('/lembur/{lembur}', [LemburController::class, 'destroy'])->name('lembur.destroy');
    
Route::patch('/lembur/{id}/status', [LemburController::class, 'updateStatus'])
    ->name('lembur.status');

});