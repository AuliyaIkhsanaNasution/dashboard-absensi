<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Shift;

class AbsensiController extends Controller
{
    /**
     * Menampilkan daftar absensi berdasarkan tanggal.
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal') ?? Carbon::now()->format('Y-m-d');


        $query = Absensi::with(['karyawan', 'perusahaan', 'shift'])
        ->whereDate('tanggal', $tanggal);


        if ($request->filled('status')) {
        $query->where('status', $request->status);
        }


        $absensiHariIni = $query->latest()->get();


        $karyawans = Karyawan::where('status', 'Aktif')
        ->orderBy('nama', 'asc')
        ->get();


        $shifts = Shift::orderBy('jam_masuk', 'asc')->get();


        return view('admin.absensi', compact(
        'absensiHariIni',
        'karyawans',
        'shifts',
        'tanggal'
));
    }

    /**
     * Menyimpan data absensi manual (Oleh Admin).
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal'     => 'required|date',
            'shift_id'    => 'required|exists:shifts,id',
            'status'      => 'required|in:Tepat Waktu,Terlambat',
            'jam_masuk'   => 'nullable',
            'jam_pulang'  => 'nullable',
            'latitude'      => '0',
            'longitude'     => '0',
            'jarak'         => '0', 
        ], [
            'karyawan_id.required' => 'Karyawan wajib dipilih',
            'shift_id.required'    => 'Shift wajib dipilih',
            'status.required'      => 'Status kehadiran wajib dipilih',
        ]);

        // Cegah absensi dobel
        $cekAbsen = Absensi::where('karyawan_id', $request->karyawan_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($cekAbsen) {
            return redirect()->back()
                ->withErrors(['error' => 'Karyawan sudah memiliki absensi di tanggal ini.']);
        }

        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        $shift    = Shift::findOrFail($request->shift_id);

        Absensi::create([
            'karyawan_id'   => $karyawan->id,
            'perusahaan_id' => $karyawan->perusahaan_id,
            'tanggal'       => $request->tanggal,
            'shift_id'      => $shift->id,
            'status'        => $request->status,
            'jam_masuk'     => $request->jam_masuk ?? $shift->jam_masuk,
            'jam_pulang'    => $request->jam_pulang ?? $shift->jam_pulang,
            'latitude'      => $request->latitude ?? 0,
            'longitude'     => $request->longitude ?? 0,
            'jarak'         => $request->jarak ?? 0,
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
    'tanggal' => 'required|date',
    'shift_id' => 'required|exists:shifts,id',
    'status' => 'required|in:Tepat Waktu,Terlambat',
    'jam_masuk' => 'nullable',
    'jam_pulang' => 'nullable',
    ]);


    $shift = Shift::findOrFail($request->shift_id);


    $absensi->update([
    'tanggal' => $request->tanggal,
    'shift_id' => $shift->id,
    'status' => $request->status,
    'jam_masuk' => $request->jam_masuk ?? $shift->jam_masuk,
    'jam_pulang' => $request->jam_pulang ?? $shift->jam_pulang,
    ]);


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