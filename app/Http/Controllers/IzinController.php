<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $allIzin   = Izin::with('karyawan')->latest()->get();
        $karyawans = Karyawan::orderBy('nama')->get(['id', 'nama', 'nip', 'jabatan']);

        return view('admin.izin', compact('allIzin', 'karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'  => 'required|exists:karyawans,id',
            'jenis_izin'   => 'required|string',
            'tanggal_izin' => 'required|date',
            'keterangan'   => 'nullable|string|max:1000',
            'dokumen'      => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $data = $request->only(['karyawan_id', 'jenis_izin', 'tanggal_izin', 'keterangan']);

        if ($request->hasFile('dokumen')) {
            $file           = $request->file('dokumen');
            $filename       = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('dokumen_izin'), $filename);
            $data['dokumen'] = $filename;
        }

        Izin::create($data);

        return redirect()->route('admin.izin')->with('success', 'Data izin berhasil ditambahkan.');
    }

    public function update(Request $request, Izin $izin)
    {
        $request->validate([
            'jenis_izin'   => 'required|string',
            'tanggal_izin' => 'required|date',
            'keterangan'   => 'nullable|string|max:1000',
            'dokumen'      => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $data = $request->only(['jenis_izin', 'tanggal_izin', 'keterangan']);

        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($izin->dokumen && file_exists(public_path('dokumen_izin/' . $izin->dokumen))) {
                unlink(public_path('dokumen_izin/' . $izin->dokumen));
            }

            $file           = $request->file('dokumen');
            $filename       = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('dokumen_izin'), $filename);
            $data['dokumen'] = $filename;
        }

        $izin->update($data);

        return redirect()->route('admin.izin')->with('success', 'Data izin berhasil diperbarui.');
    }

    public function destroy(Izin $izin)
    {
        if ($izin->dokumen && file_exists(public_path('dokumen_izin/' . $izin->dokumen))) {
            unlink(public_path('dokumen_izin/' . $izin->dokumen));
        }

        $izin->delete();

        return redirect()->route('admin.izin')->with('success', 'Data izin berhasil dihapus.');
    }
}