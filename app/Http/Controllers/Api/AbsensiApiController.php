<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiApiController extends Controller
{
    /**
     * Cek absensi hari ini
     */
    public function hariIni()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $absensi = Absensi::where('karyawan_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }

    /**
     * Absen MASUK
     */
    public function masuk(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Cek sudah absen hari ini
        $cek = Absensi::where('karyawan_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($cek) {
            return response()->json([
                'message' => 'Anda sudah absen hari ini'
            ], 409);
        }

        $request->validate([
            'shift_id'   => 'required|integer|exists:shifts,id',
            'foto_masuk' => 'required|image|max:5120',
        ]);

        // Upload foto masuk
        $fotoMasuk = $request->file('foto_masuk')
            ->store('absensi/masuk', 'public');

        $absensi = Absensi::create([
            'karyawan_id'   => $user->id,
            'perusahaan_id' => $user->perusahaan_id ?? null,
            'shift_id'      => $request->shift_id,
            'tanggal'       => now()->toDateString(),      // DATE
            'jam_masuk'     => now()->format('H:i:s'),     // TIME
            'foto_masuk'    => $fotoMasuk,
            'status'        => 'Tepat Waktu'               // ENUM
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen masuk berhasil',
            'data' => $absensi
        ], 201);
    }

    /**
     * Absen PULANG
     */
    public function pulang(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $absensi = Absensi::where('karyawan_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if (!$absensi) {
            return response()->json([
                'message' => 'Belum absen masuk'
            ], 404);
        }

        if ($absensi->jam_pulang) {
            return response()->json([
                'message' => 'Anda sudah absen pulang'
            ], 409);
        }

        $request->validate([
            'foto_keluar' => 'required|image|max:5120',
        ]);

        // Upload foto pulang
        $fotoKeluar = $request->file('foto_keluar')
            ->store('absensi/pulang', 'public');

        $absensi->update([
            'jam_pulang'  => now()->format('H:i:s'),
            'foto_keluar' => $fotoKeluar,
            'status'      => 'Tepat Waktu'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil',
            'data' => $absensi
        ]);
    }

    /**
     * Riwayat absensi
     */
    public function riwayat()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $absensi = Absensi::where('karyawan_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }
}