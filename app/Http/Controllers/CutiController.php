<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CutiController extends Controller
{
    public function index()
    {
        $allCuti = Cuti::with('karyawan')
            ->orderBy('created_at', 'desc')
            ->get();

        $karyawans = Karyawan::orderBy('nama', 'asc')->get();

        return view('admin.cuti', compact('allCuti', 'karyawans'));
    }

    public function store(Request $request)
    {
        // Sesuaikan validasi dengan nama kolom di database
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'jenis_cuti' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_masuk_kerja' => 'required|date|after:tanggal_selesai',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($request->hasFile('dokumen')) {
            $validated['dokumen'] = $request->file('dokumen')->store('cuti_dokumen', 'public');
        }

        Cuti::create($validated);

        return redirect()->route('admin.cuti')->with('success', 'Data cuti berhasil ditambahkan!');
    }

    public function update(Request $request, Cuti $cuti)
    {
        // Sesuaikan validasi dengan nama kolom di database
        $validated = $request->validate([
            'jenis_cuti' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_masuk_kerja' => 'required|date|after:tanggal_selesai',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($request->hasFile('dokumen')) {
            if ($cuti->dokumen) {
                Storage::disk('public')->delete($cuti->dokumen);
            }
            $validated['dokumen'] = $request->file('dokumen')->store('cuti_dokumen', 'public');
        }

        $cuti->update($validated);

        return redirect()->route('admin.cuti')->with('success', 'Data cuti berhasil diperbarui!');
    }

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected'
    ]);

    $cuti = Cuti::findOrFail($id);

    $cuti->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Status cuti berhasil diperbarui.');
}



    public function destroy(Cuti $cuti)
    {
        if ($cuti->dokumen) {
            Storage::disk('public')->delete($cuti->dokumen);
        }

        $cuti->delete();

        return redirect()->route('admin.cuti')->with('success', 'Data cuti berhasil dihapus!');
    }
}