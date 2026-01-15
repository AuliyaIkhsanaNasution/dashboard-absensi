<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required'
        ]);

        $karyawan = Karyawan::where('nip', $request->nip)
            ->where('status', 'Aktif')
            ->first();

        if (! $karyawan || $request->password !== $karyawan->password) {
            return response()->json([
                'message' => 'NIP atau password salah'
            ], 401);
        }

        $karyawan->tokens()->delete();

        $token = $karyawan->createToken('absensi-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'data' => [
                'id' => $karyawan->id,
                'nip' => $karyawan->nip,
                'nama' => $karyawan->nama,
                'jabatan' => $karyawan->jabatan,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}

