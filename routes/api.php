<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KaryawanAuthController;
use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\KaryawanController;
use App\Http\Controllers\Api\IzinApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// LOGIN KARYAWAN
Route::post('/login', [KaryawanAuthController::class, 'login']);

// ROUTE TERPROTEKSI (WAJIB TOKEN)
Route::middleware('auth:sanctum')->group(function () {

    // LOGOUT
    Route::post('/logout', [KaryawanAuthController::class, 'logout']);

    // ABSENSI
    Route::post('/absensi', [AbsensiController::class, 'store']);

    // IZIN (KARYAWAN)
    Route::post('/izin', [IzinApiController::class, 'store']);
    Route::get('/izin/riwayat', [IzinApiController::class, 'riwayat']);

    // OPTIONAL: DATA USER LOGIN
    Route::get('/me', function (Request $request) {
        return response()->json([
            'id' => $request->user()->id,
            'nip' => $request->user()->nip,
            'nama' => $request->user()->nama,
            'jabatan' => $request->user()->jabatan,
        ]);
    });

    // KARYAWAN
    Route::get('/karyawan/home', [KaryawanController::class, 'home']);
    Route::get('/karyawan/profile', [KaryawanController::class, 'profile']);

});
