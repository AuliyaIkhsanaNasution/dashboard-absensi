<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * DATA HOME
     */
    public function home(Request $request)
    {
        $karyawan = $request->user(); // dari token sanctum

        return response()->json([
            'status' => true,
            'data' => [
                'foto' => $karyawan->foto,
                'nama' => $karyawan->nama,
                'jabatan' => $karyawan->jabatan,
            ]
        ]);
    }

    /**
     * DATA PROFILE
     */
    public function profile(Request $request)
    {
        $karyawan = $request->user();

        return response()->json([
            'status' => true,
            'data' => [
                'foto' => $karyawan->foto,
                'nama' => $karyawan->nama,
                'jabatan' => $karyawan->jabatan,
                'alamat' => $karyawan->alamat,
                'tanggal_lahir' => $karyawan->tanggal_lahir,
                'no_wa' => $karyawan->no_wa,
            ]
        ]);
    }
}
