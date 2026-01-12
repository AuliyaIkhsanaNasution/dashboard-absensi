<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Menampilkan daftar absensi berdasarkan tanggal.
     */
    public function index(Request $request)
    {
        // Mengambil tanggal dari request, default ke hari ini jika tidak ada
        $tanggal = $request->get('tanggal') ?? Carbon::now()->format('Y-m-d');

        // Mengambil data absensi dengan relasi karyawan dan perusahaan
        $query = Absensi::with(['karyawan', 'perusahaan'])->whereDate('tanggal', $tanggal);

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absensiHariIni = $query->latest()->get();
        
        // Mengambil daftar karyawan aktif untuk pilihan di Modal
        $karyawans = Karyawan::where('status', 'Aktif')->orderBy('nama', 'asc')->get();

        return view('admin.absensi', compact('absensiHariIni', 'karyawans', 'tanggal'));
    }

    /**
     * Menyimpan data absensi manual (Oleh Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal'     => 'required|date',
            'shift'       => 'required|in:Pagi,Siang,Malam', // Validasi Enum Shift
            'status'      => 'required|in:Tepat Waktu,Terlambat', 
            'jam_masuk'   => 'nullable',
            'jam_pulang'  => 'nullable',
        ], [
            'karyawan_id.required' => 'Karyawan wajib dipilih',
            'shift.required'       => 'Shift wajib dipilih',
            'status.required'      => 'Status kehadiran wajib dipilih',
            'status.in'            => 'Status harus Tepat Waktu atau Terlambat',
        ]);

        // Proteksi agar tidak ada data ganda di tanggal yang sama
        $cekAbsen = Absensi::where('karyawan_id', $request->karyawan_id)
                           ->whereDate('tanggal', $request->tanggal)
                           ->first();

        if ($cekAbsen) {
            return redirect()->back()->withErrors(['error' => 'Karyawan tersebut sudah memiliki data absensi pada tanggal ini.']);
        }

        // Ambil data karyawan untuk mendapatkan perusahaan_id
        $karyawan = Karyawan::findOrFail($request->karyawan_id);

        // SIMPAN DATA KE TABEL ABSENSI
        Absensi::create([
            'karyawan_id'   => $request->karyawan_id,
            'perusahaan_id' => $karyawan->perusahaan_id,
            'tanggal'       => $request->tanggal,
            'shift'         => $request->shift, // Kolom shift sekarang tersimpan
            'status'        => $request->status,
            'jam_masuk'     => $request->jam_masuk,
            'jam_pulang'    => $request->jam_pulang,
        ]);

        return redirect()->back()->with('success', 'Data absensi berhasil ditambahkan!');
    }

    /**
     * Memperbarui data absensi yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'shift'      => 'required|in:Pagi,Siang,Malam', // Validasi shift saat update
            'status'     => 'required|in:Tepat Waktu,Terlambat',
            'jam_masuk'  => 'nullable',
            'jam_pulang' => 'nullable',
        ]);

        // Update semua data termasuk shift
        $absensi->update($validated);

        return redirect()->back()->with('success', 'Data absensi berhasil diperbarui!');
    }

    /**
     * Menghapus data absensi.
     */
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->back()->with('success', 'Data absensi berhasil dihapus!');
    }
}