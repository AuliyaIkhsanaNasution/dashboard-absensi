<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;

class ShiftApiController extends Controller
{
    /**
     * Shift yang boleh dipilih saat absen
     */
  public function shiftsForAbsensi()
{
    $shifts = Shift::select(
            'id',
            'nama_shift',
            'jam_masuk',
            'jam_pulang',
            'toleransi_menit'
        )
        ->orderBy('jam_masuk')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $shifts
    ]);
}
}