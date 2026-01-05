<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// 2. Auth Routes (Login & Logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 3. Route untuk halaman report pelanggan (Luar Admin)
Route::get('/report', function () {
    return view('report');
})->name('report');

// 4. Admin Routes (Dikumpulkan dalam satu Group)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return view('admin.dashboard', [
            'user' => $user,
            'totalKaryawan' => 0, // Nanti diisi dengan data dari Model
            'hadirHariIni' => 0,
            'terlambat' => 0,
            'izin' => 0,
            'absensiHariIni' => []
        ]);
    })->name('dashboard');
    
    // Kelola Karyawan
    Route::get('/karyawan', function () {
        return view('admin.karyawan', ['karyawan' => []]);
    })->name('karyawan');

    Route::post('/karyawan/store', function () {
        return redirect()->back()->with('success', 'Data karyawan berhasil disimpan');
    })->name('karyawan.store');
    
    // Kelola Absensi
    Route::get('/absensi', function () {
        return view('admin.absensi', ['absensi' => [], 'karyawan' => []]);
    })->name('absensi');
    
    // Kelola Laporan Kehadiran
    Route::get('/laporan', function () {
        return view('admin.laporan', [
            'totalHadir' => 0,
            'totalTerlambat' => 0,
            'totalIzin' => 0,
            'totalSakit' => 0,
            'laporan' => []
        ]);
    })->name('laporan');

    // Pengaturan Perusahaan (Jika dipanggil di Sidebar)
    Route::get('/perusahaan', function () {
        return view('admin.perusahaan');
    })->name('perusahaan');

    
});

use App\Http\Controllers\KaryawanController;

// Route untuk menampilkan halaman
Route::get('/admin/karyawan', [KaryawanController::class, 'index'])->name('admin.karyawan');

// Route untuk proses simpan (Inilah yang membuat tombol simpan bekerja)
Route::post('/admin/karyawan/store', [KaryawanController::class, 'store'])->name('admin.karyawan.store');