<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    /**
     * Menampilkan halaman dengan data karyawan
     */
    public function index() {
        $data = Karyawan::with('perusahaan')->latest()->get();
        $perusahaans = Perusahaan::orderBy('nama_pt', 'asc')->get();
        return view('admin.karyawan', compact('data', 'perusahaans'));
    }

    /**
     * Menyimpan data karyawan baru
     */
    public function store(Request $request) {
        // Validasi input dengan foto
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'nip' => 'required|unique:karyawans,nip',
            'nama_lengkap' => 'required|string|max:255',
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jabatan' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
        ], [
            'foto.image' => 'File harus berupa gambar',    
            'foto.mimes' => 'Format foto harus: jpeg, png, jpg',
            'foto.max' => 'Ukuran foto maksimal 5MB',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'perusahaan_id.required' => 'Perusahaan wajib dipilih',
            'perusahaan_id.exists' => 'Perusahaan tidak valid',
            'jabatan.required' => 'Jabatan wajib diisi',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi',
        ]);

        // Handle upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
        }

        // Simpan data karyawan
        Karyawan::create([
            'foto' => $fotoPath,
            'nip' => $request->nip,
            'nama' => $request->nama_lengkap,
            'perusahaan_id' => $request->perusahaan_id,
            'jabatan' => $request->jabatan,
            'no_wa' => $request->nomor_wa,
            'status' => 'Aktif'
        ]);

        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    /**
     * Update data karyawan
     */
    public function update(Request $request, $id) {
        $karyawan = Karyawan::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'nip' => 'required|unique:karyawans,nip,' . $id,
            'nama_lengkap' => 'required|string|max:255',
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jabatan' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
        ], [
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus: jpeg, png, jpg',
            'foto.max' => 'Ukuran foto maksimal 5MB',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'perusahaan_id.required' => 'Perusahaan wajib dipilih',
            'perusahaan_id.exists' => 'Perusahaan tidak valid',
            'jabatan.required' => 'Jabatan wajib diisi',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi',
        ]);

        // Data yang akan diupdate
        $dataToUpdate = [
            'nip' => $request->nip,
            'nama' => $request->nama_lengkap,
            'perusahaan_id' => $request->perusahaan_id,
            'jabatan' => $request->jabatan,
            'no_wa' => $request->nomor_wa,
        ];

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            
            // Upload foto baru
            $dataToUpdate['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        // Update data
        $karyawan->update($dataToUpdate);

        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil diupdate!');
    }

    /**
     * Hapus data karyawan
     */
    public function destroy($id) {
        $karyawan = Karyawan::findOrFail($id);
        
        // Hapus foto jika ada
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }
        
        // Hapus data karyawan
        $karyawan->delete();

        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil dihapus!');
    }

    /**
     * Ubah status karyawan (Aktif/Non-Aktif)
     */
    public function toggleStatus($id) {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->status = $karyawan->status === 'Aktif' ? 'Non-Aktif' : 'Aktif';
        $karyawan->save();

        return redirect()->route('admin.karyawan')->with('success', 'Status karyawan berhasil diubah!');
    }
}