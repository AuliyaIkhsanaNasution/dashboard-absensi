<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Lembur;
use Illuminate\Http\Request;

class LemburController extends Controller
{
    public function index()
{
    // Mengambil semua data lembur beserta relasi karyawan
    $allLembur = \App\Models\Lembur::with('karyawan')->latest()->get();
    
    // Mengambil daftar karyawan untuk pilihan di modal "Tambah Lembur"
    $karyawans = \App\Models\Karyawan::all();

    return view('admin.lembur', compact('allLembur', 'karyawans'));
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected'
    ]);

    $lembur = Lembur::findOrFail($id);

    $lembur->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Status lembur berhasil diperbarui.');
}

public function destroy($id)
{
    $lembur = Lembur::findOrFail($id);

    // jika ada dokumen lampiran bisa juga dihapus dari storage
    if ($lembur->dokumen && Storage::exists('public/' . $lembur->dokumen)) {
        Storage::delete('public/' . $lembur->dokumen);
    }

    $lembur->delete();

    return back()->with('success', 'Data lembur berhasil dihapus.');
}
}