<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeKaryawanMail;
use Illuminate\Support\Str;

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
        // Validasi input dengan foto dan email
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'nip' => 'required|unique:karyawans,nip',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans,email',
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jabatan' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
            'alamat' => 'nullable|string',  
            'tanggal_lahir' => 'nullable|date',  
            'password' => 'required|string|min:6',
        ], [
            'foto.image' => 'File harus berupa gambar',    
            'foto.mimes' => 'Format foto harus: jpeg, png, jpg',
            'foto.max' => 'Ukuran foto maksimal 5MB',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'perusahaan_id.required' => 'Perusahaan wajib dipilih',
            'perusahaan_id.exists' => 'Perusahaan tidak valid',
            'jabatan.required' => 'Jabatan wajib diisi',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi',
            'alamat.string' => 'Alamat harus berupa teks',  
            'tanggal_lahir.date' => 'Format tanggal tidak valid', 
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Simpan password plain text untuk email (sebelum di-hash)
        $plainPassword = $request->password;

        // Handle upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
        }

        // Simpan data karyawan
        $karyawan = Karyawan::create([
            'foto' => $fotoPath,
            'nip' => $request->nip,
            'nama' => $request->nama_lengkap,
            'email' => $request->email,
            'perusahaan_id' => $request->perusahaan_id,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_wa' => $request->nomor_wa,
            'password' => Hash::make($plainPassword),
            'status' => 'Aktif'
        ]);

        // Kirim email otomatis
        try {
            Mail::to($karyawan->email)->send(new WelcomeKaryawanMail($karyawan, $plainPassword));
            
            return redirect()->route('admin.karyawan')->with('success', 
                'Data karyawan berhasil ditambahkan! Email dengan NIP dan Password telah dikirim ke ' . $karyawan->email);
        } catch (\Exception $e) {
            // Jika email gagal terkirim, tetap simpan data tapi beri notifikasi
            return redirect()->route('admin.karyawan')->with('warning', 
                'Data karyawan berhasil ditambahkan, tetapi email gagal dikirim. Error: ' . $e->getMessage());
        }
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
            'email' => 'required|email|unique:karyawans,email,' . $id,
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'jabatan' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'password' => 'nullable|string|min:6',
        ], [
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus: jpeg, png, jpg',
            'foto.max' => 'Ukuran foto maksimal 5MB',
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'perusahaan_id.required' => 'Perusahaan wajib dipilih',
            'perusahaan_id.exists' => 'Perusahaan tidak valid',
            'jabatan.required' => 'Jabatan wajib diisi',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'tanggal_lahir.date' => 'Format tanggal tidak valid',  
            'password.min' => 'Password minimal 6 karakter'
        ]);

        // Data yang akan diupdate
        $dataToUpdate = [
            'nip' => $request->nip,
            'nama' => $request->nama_lengkap,
            'email' => $request->email,
            'perusahaan_id' => $request->perusahaan_id,
            'jabatan' => $request->jabatan,
            'no_wa' => $request->nomor_wa,
            'alamat' => $request->alamat,  
            'tanggal_lahir' => $request->tanggal_lahir,
        ];

        // Jika password diisi, update juga
        if ($request->filled('password')) {  
            $dataToUpdate['password'] = bcrypt($request->password);
        }

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

    /**
     * Kirim ulang email welcome ke karyawan
     */
    public function resendEmail($id) {
        $karyawan = Karyawan::with('perusahaan')->findOrFail($id);
        
        // Generate password baru
        $newPassword = Str::random(10);
        
        // Update password
        $karyawan->password = bcrypt($newPassword);
        $karyawan->save();
        
        // Kirim email
        try {
            Mail::to($karyawan->email)->send(new WelcomeKaryawanMail($karyawan, $newPassword));
            
            return redirect()->route('admin.karyawan')->with('success', 
                'Email berhasil dikirim ulang ke ' . $karyawan->email . ' dengan password baru!');
        } catch (\Exception $e) {
            return redirect()->route('admin.karyawan')->with('error', 
                'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}