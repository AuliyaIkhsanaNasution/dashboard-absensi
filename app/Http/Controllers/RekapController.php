<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari request, default bulan dan tahun saat ini
        $bulanInput = $request->input('bulan', date('m'));
        $tahunInput = $request->input('tahun', date('Y'));
        $perusahaanId = $request->input('perusahaan_id'); // Filter perusahaan
        
        // Buat Carbon untuk bulan yang dipilih
        $bulan = Carbon::create($tahunInput, $bulanInput, 1, 0, 0, 0, 'Asia/Jakarta');
        $start = $bulan->copy()->startOfMonth()->startOfDay();
        
        // Jika bulan yang dipilih adalah bulan ini, maka end = hari ini
        // Jika bulan lalu, maka end = akhir bulan tersebut
        if ($bulan->isSameMonth(Carbon::now('Asia/Jakarta'))) {
            $end = Carbon::now('Asia/Jakarta')->endOfDay();
        } else {
            $end = $bulan->copy()->endOfMonth()->endOfDay();
        }
        
        // Ambil semua karyawan dengan filter perusahaan jika ada
        $queryKaryawan = Karyawan::query();
        
        if ($perusahaanId) {
            $queryKaryawan->where('perusahaan_id', $perusahaanId);
        }
        
        $semuaKaryawan = $queryKaryawan->get();
        
        $rekapData = [];
        
        foreach ($semuaKaryawan as $karyawan) {
            $karyawanId = $karyawan->id;
            
            // Ambil absensi bulan ini
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
                    // Tidak absen dan tidak izin → tidak hadir
                    $tidakHadir++;
                }
            }
            
            // Total kehadiran
            $totalHadir = $tepatWaktu + $terlambat + $izin;
            
            // Persentase kehadiran
            $persentaseKehadiran = $hariKerja > 0 ? round(($totalHadir / $hariKerja) * 100, 1) : 0;
            
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
                'persentase_kehadiran' => $persentaseKehadiran
            ];
        }
        
        // Hitung total keseluruhan
        $totalData = [
            'tepat_waktu' => collect($rekapData)->sum('tepat_waktu'),
            'terlambat' => collect($rekapData)->sum('terlambat'),
            'izin' => collect($rekapData)->sum('izin'),
            'tidak_hadir' => collect($rekapData)->sum('tidak_hadir'),
        ];
        
        // Hitung hari kerja total
        $jumlahHariKerja = 0;
        $tmp = $start->copy();
        while ($tmp <= $end) {
            $jumlahHariKerja++;
            $tmp->addDay();
        }
        
        // Ambil semua perusahaan untuk dropdown
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
}