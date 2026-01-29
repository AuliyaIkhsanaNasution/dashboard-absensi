<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Menampilkan daftar shift.
     * Route: GET /shift
     */
    public function index()
    {
        $shifts = Shift::orderBy('jam_masuk', 'asc')->get();
        // Sesuai permintaan Anda: view mengarah ke admin/shift.blade.php
        return view('admin.shift', compact('shifts'));
    }

    /**
     * Menyimpan shift baru.
     * Route: POST /shift
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_shift'      => 'required|string|max:50',
            'jam_masuk'       => 'required|date_format:H:i',
            'jam_pulang'      => 'required|date_format:H:i',
            'toleransi_menit' => 'required|integer|min:0',
        ]);

        Shift::create($validated);

        return redirect()->route('admin.shift')->with('success', 'Shift baru berhasil disimpan!');
    }

    /**
     * Memperbarui data shift.
     * Route: PUT /shift/{id}
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_shift'      => 'required|string|max:50',
            'jam_masuk'       => 'required|date_format:H:i',
            'jam_pulang'      => 'required|date_format:H:i',
            'toleransi_menit' => 'required|integer|min:0',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update($validated);

        return redirect()->route('admin.shift')->with('success', 'Data shift berhasil diperbarui!');
    }

    /**
     * Menghapus data shift.
     * Route: DELETE /shift/{id}
     */
    public function destroy($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->delete();
            return redirect()->route('admin.shift')->with('success', 'Shift berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.shift')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}