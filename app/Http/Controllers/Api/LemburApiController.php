<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lembur;

class LemburApiController extends Controller
{
    /**
     * Riwayat lembur karyawan (berdasarkan TOKEN)
     */
    public function riwayat()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $lembur = Lembur::where('karyawan_id', $user->id)
            ->orderBy('tgl_lembur', 'desc')
            ->get();

        $lembur->transform(function ($item) {
            $item->dokumen = $item->dokumen
                ? url('storage/' . $item->dokumen)
                : null;

            return $item;
        });

        return response()->json($lembur);
    }

    /**
     * Ajukan lembur
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
            'tgl_lembur'   => 'required|date',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'kategori'     => 'required|in:Hari Kerja,Hari Libur',
            'keterangan'   => 'required|string',
            'dokumen'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            $dokumenPath = $request->file('dokumen')->store('dokumen_lembur', 'public');
        }

        $lembur = Lembur::create([
            'karyawan_id' => $user->id,
            'tgl_lembur'  => $request->tgl_lembur,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kategori'    => $request->kategori,
            'keterangan'  => $request->keterangan,
            'dokumen'     => $dokumenPath,
            'status'      => 'pending'
        ]);

        return response()->json([
            'message' => 'Pengajuan lembur berhasil',
            'data'    => $lembur
        ], 201);
    }

    /**
     * Daftar kategori lembur (untuk dropdown aplikasi)
     */
    public function kategori()
    {
        $kategori = [
            'Hari Kerja',
            'Hari Libur'
        ];

        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }
}
