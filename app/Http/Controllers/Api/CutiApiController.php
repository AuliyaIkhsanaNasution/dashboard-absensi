<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cuti;
use Illuminate\Support\Facades\Validator;

class CutiApiController extends Controller
{
    /**
     * Riwayat cuti karyawan (berdasarkan TOKEN)
     */
    public function riwayat()
{
    $user = auth('sanctum')->user();

    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    $cuti = Cuti::where('karyawan_id', $user->id)
        ->orderBy('tanggal_mulai', 'desc')
        ->get();

    $cuti->transform(function ($item) {
        $item->dokumen = $item->dokumen
            ? url('storage/' . $item->dokumen)
            : null;

        return $item;
    });

    return response()->json($cuti);
}

    /**
     * Ajukan cuti
     */
    public function store(Request $request)
{
    $user = auth('sanctum')->user();

    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    $request->validate([
        'jenis_cuti'          => 'required',
        'tanggal_mulai'       => 'required|date',
        'tanggal_selesai'     => 'required|date|after_or_equal:tanggal_mulai',
        'tanggal_masuk_kerja' => 'required|date|after:tanggal_selesai',
        'keterangan'          => 'required|string',
        'dokumen'             => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    $dokumenPath = null;
    if ($request->hasFile('dokumen') && $request->file('dokumen')->isValid()) {
    $dokumenPath = $request->file('dokumen')->store('dokumen_cuti', 'public');
    }

    $cuti = Cuti::create([
        'karyawan_id'         => $user->id,
        'jenis_cuti'          => $request->jenis_cuti,
        'tanggal_mulai'       => $request->tanggal_mulai,
        'tanggal_selesai'     => $request->tanggal_selesai,
        'tanggal_masuk_kerja' => $request->tanggal_masuk_kerja,
        'keterangan'          => $request->keterangan,
        'dokumen'             => $dokumenPath,
        'status'              => 'pending'
    ]);

    return response()->json([
        'message' => 'Pengajuan cuti berhasil',
        'data' => $cuti
    ], 201);
}


    /**
     * Daftar jenis cuti (untuk dropdown aplikasi)
     */
    public function jenisCuti()
    {
        $jenisCuti = [
            'Cuti Tahunan',
            'Cuti Melahirkan',
            'Cuti Menikah',
            'Cuti Besar',
            'Cuti Tanpa Gaji'
        ];

        return response()->json([
            'success' => true,
            'data' => $jenisCuti
        ]);
    }
}
