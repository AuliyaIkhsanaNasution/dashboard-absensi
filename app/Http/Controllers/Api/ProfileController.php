<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'nama' => $user->nama,
            'alamat' => $user->alamat,
            'no_wa' => $user->no_wa,
            'tanggal_lahir' => $user->tanggal_lahir,
            'foto' => $user->foto,
        ]);
    }

        public function catat(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'nama' => $user->nama,
            'alamat' => $user->alamat,
            'no_wa' => $user->no_wa,
            'tanggal_lahir' => $user->tanggal_lahir,
            'nip' => $user->nip,
            'jabatan' => $user->jabatan,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $user->update($request->only([
            'nama',
            'alamat',
            'no_wa',
            'tanggal_lahir',
        ]));

        return response()->json([
            'message' => 'Profile berhasil diperbarui'
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // Hapus foto lama
        if ($user->foto) {
            $oldPath = str_replace(asset('storage') . '/', '', $user->foto);
            Storage::disk('public')->delete($oldPath);
        }

        // Simpan foto baru
        $path = $request->file('foto')->store('profile', 'public');

        $user->update([
            'foto' => $path
        ]);

        return response()->json([
            'message' => 'Foto profile berhasil diperbarui',
            'foto' => $user->foto
        ]);
    }
}
