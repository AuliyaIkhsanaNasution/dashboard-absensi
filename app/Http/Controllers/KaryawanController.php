<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index() {
        $data = Karyawan::latest()->get();
        return view('admin.karyawan', compact('data'));
    }

    public function store(Request $request) {
        // Validasi agar tidak error saat data kosong
        $request->validate([
            'nip' => 'required|unique:karyawans',
            'nama' => 'required',
            'jabatan' => 'required',
            'penempatan' => 'required',
            'no_wa' => 'required',
        ]);

        // Simpan data
        Karyawan::create([
            'nip' => $request->nip,
            'nama' => $request->nama_lengkap, // Pastikan kolom ini ada di database
            'jabatan' => $request->jabatan,
            'penempatan' => $request->penempatan,
            'no_wa' => $request->no_wa,
            'status' => 'Aktif'
        ]);

        return redirect()->route('admin.karyawan')->with('success', 'Data berhasil disimpan!');
    }
}