<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Perusahaan;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiApiController extends Controller
{
    /**
     * =====================
     * ABSEN MASUK
     * =====================
     */
    public function masuk(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        Carbon::setLocale('id');
        $now = Carbon::now('Asia/Jakarta');

        $sudahAbsen = Absensi::where('karyawan_id', $user->id)
            ->whereDate('tanggal', $now->toDateString())
            ->first();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah absen hari ini'
            ], 409);
        }

        $request->validate([
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric',
            'is_mock_location' => 'required|boolean',
            'foto_masuk'       => 'required|image|max:5120',
            'shift_id'         => 'required|exists:shifts,id', // ✅ WAJIB INPUT
        ]);

        $perusahaan = Perusahaan::findOrFail($user->perusahaan_id);

        $jarak = $this->hitungJarak(
            $request->latitude,
            $request->longitude,
            $perusahaan->latitude,
            $perusahaan->longitude
        );

        if ($jarak > $perusahaan->radius_absen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius absensi',
                'jarak'   => $jarak,
                'radius'  => $perusahaan->radius_absen
            ], 403);
        }

        $fotoMasuk = $request->file('foto_masuk')
            ->store('absensi/masuk', 'public');

        $jamMasuk = $now->format('H:i:s');

        // 🎯 AMBIL SHIFT DARI INPUT USER
        $shift = Shift::findOrFail($request->shift_id);

        // 🎯 HITUNG STATUS OTOMATIS BERDASARKAN SHIFT
        $status = $shift->cekStatus($jamMasuk);

        $absensi = Absensi::create([
            'karyawan_id'   => $user->id,
            'perusahaan_id' => $user->perusahaan_id,
            'tanggal'       => $now->toDateString(),
            'jam_masuk'     => $jamMasuk,
            'foto_masuk'    => $fotoMasuk,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'jarak'         => $jarak,
            'status'        => $status, // ✅ OTOMATIS
            'shift_id'      => $shift->id, // ✅ DARI INPUT
        ]);

        // Load relasi
        $absensi->load(['karyawan', 'shift', 'perusahaan']);

        return response()->json([
            'success' => true,
            'message' => 'Absen masuk berhasil',
            'data'    => $absensi
        ], 201);
    }

    /**
     * =====================
     * ABSEN PULANG
     * =====================
     */
    public function pulang(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $now = Carbon::now('Asia/Jakarta');

        $absensi = Absensi::where('karyawan_id', $user->id)
            ->whereDate('tanggal', $now->toDateString())
            ->with(['karyawan', 'shift', 'perusahaan'])
            ->first();

        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Belum absen masuk'
            ], 404);
        }

        if ($absensi->jam_pulang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah absen pulang'
            ], 409);
        }

        $request->validate([
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
            'is_mock_location' => 'required|boolean',
            'foto_keluar'  => 'required|image|max:5120',
        ]);

        $perusahaan = Perusahaan::findOrFail($user->perusahaan_id);

        $jarak = $this->hitungJarak(
            $request->latitude,
            $request->longitude,
            $perusahaan->latitude,
            $perusahaan->longitude
        );

        if ($jarak > $perusahaan->radius_absen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius absensi'
            ], 403);
        }

        $fotoKeluar = $request->file('foto_keluar')
            ->store('absensi/pulang', 'public');

        $absensi->update([
            'jam_pulang'  => $now->format('H:i:s'),
            'foto_keluar' => $fotoKeluar,
        ]);
        
        $absensi->refresh();
        $absensi->load(['karyawan', 'shift', 'perusahaan']);
        
        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil',
            'data'    => $absensi
        ]);
    }

    /**
     * =====================
     * RIWAYAT ABSENSI
     * =====================
     */
    public function riwayat()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $absensi = Absensi::where('karyawan_id', $user->id)
            ->with(['karyawan', 'shift', 'perusahaan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'meta' => [
                'current_page' => $absensi->currentPage(),
                'last_page'    => $absensi->lastPage(),
                'per_page'     => $absensi->perPage(),
                'total'        => $absensi->total(),
            ],
            'data' => $absensi->items()
        ]);
    }

    /**
     * =====================
     * GET DAFTAR SHIFT (Endpoint Baru)
     * =====================
     */
    public function getShifts()
    {
        $shifts = Shift::orderBy('jam_masuk', 'asc')->get();

        return response()->json([
            'success' => true,
            'data'    => $shifts
        ]);
    }

    /**
     * =====================
     * HITUNG JARAK (meter)
     * =====================
     */
    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
             cos(deg2rad($lat1)) *
             cos(deg2rad($lat2)) *
             sin($dLon / 2) ** 2;

        return (int) round($earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

        /**
     * =====================
     * ABSENSI HARI INI
     * =====================
     */
    public function hariIni()
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $absensi = Absensi::where('karyawan_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return response()->json([
                "success" => true,
                "data" => null
            ]);
        }

        // HITUNG TOTAL KERJA
        $totalKerja = null;
        if ($absensi->jam_masuk && $absensi->jam_pulang) {
            $mulai   = Carbon::parse($absensi->jam_masuk);
            $selesai = Carbon::parse($absensi->jam_pulang);
            $diff    = $mulai->diff($selesai);

            $totalKerja = $diff->h . "j " . $diff->i . "m";
        }

        return response()->json([
            "success" => true,
            "data" => [
                "id"             => $absensi->id,
                "karyawan_id"    => $absensi->karyawan_id,
                "shift_id"       => $absensi->shift_id,
                "perusahaan_id"  => $absensi->perusahaan_id,
                "tanggal"        => $absensi->tanggal,
                "jam_masuk"      => $absensi->jam_masuk,
                "foto_masuk"     => $absensi->foto_masuk,
                "jam_pulang"     => $absensi->jam_pulang,
                "foto_keluar"    => $absensi->foto_keluar,
                "status"         => $absensi->status,
                "created_at"     => $absensi->created_at,
                "updated_at"     => $absensi->updated_at,
                "total_kerja"    => $totalKerja
            ]
        ]);
    }
}