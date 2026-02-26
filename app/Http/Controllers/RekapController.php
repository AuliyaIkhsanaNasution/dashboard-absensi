<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\RekapKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    // ============================
    //  HALAMAN REKAP
    // ============================
    public function index(Request $request)
    {
        $bulanInput = $request->input('bulan', date('m'));
        $tahunInput = $request->input('tahun', date('Y'));
        $perusahaanId = $request->input('perusahaan_id');
        
        $bulan = Carbon::create($tahunInput, $bulanInput, 1, 0, 0, 0, 'Asia/Jakarta');
        $start = $bulan->copy()->startOfMonth()->startOfDay();

        $end = $bulan->isSameMonth(Carbon::now('Asia/Jakarta'))
            ? Carbon::now('Asia/Jakarta')->endOfDay()
            : $bulan->copy()->endOfMonth()->endOfDay();

        $rekapData = $this->getRekapKehadiran($perusahaanId, $bulanInput, $tahunInput);

        // Hitung total keseluruhan
        $totalData = [
            'tepat_waktu' => collect($rekapData)->sum('tepat_waktu'),
            'terlambat' => collect($rekapData)->sum('terlambat'),
            'izin' => collect($rekapData)->sum('izin'),
            'tidak_hadir' => collect($rekapData)->sum('tidak_hadir'),
        ];

        // Hitung total hari kerja
        $jumlahHariKerja = 0;
        $tmp = $start->copy();
        while ($tmp <= $end) {
            $jumlahHariKerja++;
            $tmp->addDay();
        }

        $perusahaans = Perusahaan::orderBy('nama_pt')->get();

        return view('admin.rekap', [
            'karyawan' => $rekapData,
            'totalData' => $totalData,
            'bulan' => $bulanInput,
            'tahun' => $tahunInput,
            'jumlahHariKerja' => $jumlahHariKerja,
            'periodeText' => $bulan->isSameMonth(Carbon::now('Asia/Jakarta'))
                ? 'Sampai Hari Ini'
                : 'Bulan Penuh',
            'perusahaans' => $perusahaans,
            'perusahaanId' => $perusahaanId
        ]);
    }

    // ============================
    //  EXPORT EXCEL
    // ============================
    public function export(Request $request)
    {
        $perusahaanId = $request->perusahaan_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $data = $this->getRekapKehadiran($perusahaanId, $bulan, $tahun);

        return Excel::download(
            new RekapKehadiranExport($data),
            'rekap_kehadiran_' . $bulan . '_' . $tahun . '.xlsx'
        );
    }

    // ============================
    //  FUNCTION REKAP
    // ============================
    private function getRekapKehadiran($perusahaanId, $bulanInput, $tahunInput)
    {
        $bulan = Carbon::create($tahunInput, $bulanInput, 1, 0, 0, 0, 'Asia/Jakarta');
        $start = $bulan->copy()->startOfMonth()->startOfDay();

        $end = $bulan->isSameMonth(now('Asia/Jakarta'))
            ? now('Asia/Jakarta')->endOfDay()
            : $bulan->copy()->endOfMonth()->endOfDay();

        $queryKaryawan = Karyawan::query();
        if ($perusahaanId) {
            $queryKaryawan->where('perusahaan_id', $perusahaanId);
        }

        $semuaKaryawan = $queryKaryawan->get();
        $rekapData = [];

        foreach ($semuaKaryawan as $karyawan) {

            $karyawanId = $karyawan->id;

            $absensis = Absensi::with('shift')
                ->where('karyawan_id', $karyawanId)
                ->whereBetween('tanggal', [$start, $end])
                ->get();

            $izins = Izin::where('karyawan_id', $karyawanId)
                ->whereBetween('tanggal_izin', [$start, $end])
                ->get()
                ->keyBy('tanggal_izin');

            $tepatWaktu = 0;
            $terlambat = 0;
            $izin = 0;
            $tidakHadir = 0;

            $hariKerja = 0;
            $tmp = $start->copy();
            while ($tmp <= $end) {
                $hariKerja++;
                $tmp->addDay();
            }

            for ($date = $start->copy(); $date <= $end; $date->addDay()) {

                $tanggal = $date->format('Y-m-d');

                if ($izins->has($tanggal)) {
                    $izin++;
                    continue;
                }

                $absen = Absensi::with('shift')
                    ->where('karyawan_id', $karyawanId)
                    ->whereDate('tanggal', $tanggal)
                    ->first();

                if ($absen) {
                    $shift = $absen->shift;

                    if (!$shift) {
                        $tepatWaktu++;
                        continue;
                    }

                    $jamMasukShift = $shift->jam_masuk;
                    $toleransiMenit = $shift->toleransi_menit ?? 0;

                    $jamBatas = Carbon::parse($jamMasukShift)
                        ->addMinutes($toleransiMenit)
                        ->format('H:i:s');

                    if ($absen->jam_masuk <= $jamBatas) {
                        $tepatWaktu++;
                    } else {
                        $terlambat++;
                    }

                } else {
                    $tidakHadir++;
                }
            }

            $totalHadir = $tepatWaktu + $terlambat + $izin;
            $persentase = $hariKerja > 0 ? round(($totalHadir / $hariKerja) * 100, 1) : 0;

            $rekapData[] = [
                'id' => $karyawan->id,
                'nip' => $karyawan->nip,
                'nama' => $karyawan->nama,
                'jabatan' => $karyawan->jabatan,
                'perusahaan' => $karyawan->perusahaan->nama_pt ?? '-',
                'tepat_waktu' => $tepatWaktu,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'tidak_hadir' => $tidakHadir,
                'total_hadir' => $totalHadir,
                'persentase_kehadiran' => $persentase
            ];
        }

        return $rekapData;
    }
}