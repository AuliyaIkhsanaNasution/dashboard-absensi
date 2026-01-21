<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IzinApiController extends Controller
{
    /**
     * Karyawan mengajukan izin (Android)
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_izin' => 'required|in:Izin sakit tanpa surat dokter,Izin sakit dengan surat dokter,Izin keperluan keluarga,Izin mengurus dokumen,Izin pulang cepat',
            'tanggal_izin' => 'required|date',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $user = auth('sanctum')->user(); // 🔥 FIX UTAMA

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $dokumen = null;
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumen = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('dokumen_izin'), $dokumen);
        }

        $izin = Izin::create([
            'karyawan_id' => $user->id,
            'jenis_izin' => trim($request->jenis_izin),
            'tanggal_izin' => $request->tanggal_izin,
            'keterangan' => $request->keterangan,
            'dokumen' => $dokumen
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Izin berhasil diajukan',
            'data' => [
                'id' => $izin->id,
                'dokumen' => $dokumen
                    ? url('dokumen_izin/' . $dokumen)
                    : null,
            ]
        ], 201);

    }

    /**
     * Riwayat izin karyawan (Android)
     */
    public function riwayat()
    {
        $user = auth('sanctum')->user(); // 🔥 FIX UTAMA

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $izin = Izin::where('karyawan_id', $user->id)
            ->orderBy('tanggal_izin', 'desc')
            ->get();
        
            $izin->transform(function ($item) {
        $item->dokumen = $item->dokumen
            ? url('dokumen_izin/' . $item->dokumen)
            : null;
        return $item;
    });

        return response()->json($izin);
    }

        public function jenisIzin()
        {
            // Ambil enum dari kolom jenis_izin
            $type = DB::select("SHOW COLUMNS FROM izins WHERE Field = 'jenis_izin'")[0]->Type;

            // Bersihkan enum menjadi array
            preg_match('/^enum\((.*)\)$/', $type, $matches);
            $enum = [];
            if (isset($matches[1])) {
                $enum = array_map(function ($value) {
                    return trim($value, "'");
                }, explode(',', $matches[1]));
            }

            return response()->json([
                'success' => true,
                'data' => $enum
            ]);
        }
}
