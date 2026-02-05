<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapAbsensiApiController extends Controller
{
    public function rekap(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Ambil karyawan
        $karyawan = Karyawan::find($user->id);
        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan tidak ditemukan'], 404);
        }

        // Bulan aktif
        $bulan  = Carbon::now('Asia/Jakarta');
        $start  = $bulan->copy()->startOfMonth()->startOfDay();
        $end = Carbon::now('Asia/Jakarta'); // hanya sampai hari ini


        $karyawanId = $user->id;

        // Ambil absensi bulan ini (ini hanya cache, pencarian utama pakai whereDate)
        $absensis = Absensi::with('shift')
            ->where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        // Ambil izin bulan ini
        $izins = Izin::where('karyawan_id', $karyawanId)
            ->whereBetween('tanggal_izin', [$start, $end])
            ->get()
            ->keyBy('tanggal_izin');

        // Variabel rekap
        $tepatWaktu = 0;
        $terlambat  = 0;
        $izin       = 0;
        $tidakHadir = 0;

        // Hitung hari kerja (loop manual agar akurat)
        $hariKerja = 0;
        $tmp = $start->copy();
        while ($tmp <= $end) {
            $hariKerja++;
            $tmp->addDay();
        }

        // Loop per tanggal
        for ($date = $start->copy(); $date <= $end; $date->addDay()) {

            $tanggal = $date->format('Y-m-d');

            // Jika izin
            if ($izins->has($tanggal)) {
                $izin++;
                continue;
            }

            // Cari absensi berdasarkan tanggal (datetime aman)
            $absen = Absensi::with('shift')
                ->where('karyawan_id', $karyawanId)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if ($absen) {
                $shift = $absen->shift;

                // Jika shift tidak ada → tetap dianggap hadir dan tepat waktu
                if (!$shift) {
                    $tepatWaktu++;
                    continue;
                }

                $jamMasukShift  = $shift->jam_masuk;
                $toleransiMenit = $shift->toleransi_menit;

                $jamBatas = Carbon::parse($jamMasukShift)
                    ->addMinutes($toleransiMenit)
                    ->format('H:i:s');

                if ($absen->jam_masuk <= $jamBatas) {
                    $tepatWaktu++;
                } else {
                    $terlambat++;
                }

            } else {
                // Tidak absen dan tidak izin → tidak hadir
                $tidakHadir++;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'bulan'        => $bulan->format('Y-m'),
                'hari_kerja'   => $hariKerja,
                'tepat_waktu'  => $tepatWaktu,
                'terlambat'    => $terlambat,
                'izin'         => $izin,
                'tidak_hadir'  => $tidakHadir,
            ]
        ]);
    }
}
