<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
    public function index()
    {
        // Ambil semua data perusahaan agar variabel $perusahaans terdefinisi
        $perusahaans = Perusahaan::all(); 
        return view('admin.perusahaan', compact('perusahaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pt' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'alamat' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius_absen' => 'required|integer|min:1|max:5000',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        Perusahaan::create($data);

        return redirect()->back()->with('success', 'Data perusahaan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
        'nama_pt'      => 'required|string|max:255',
        'email'        => 'required|email',
        'telepon'      => 'required|string|max:20',
        'alamat'       => 'required|string',
        'latitude'     => 'required|numeric|between:-90,90',
        'longitude'    => 'required|numeric|between:-180,180',
        'radius_absen' => 'required|integer|min:1|max:5000',
    ]);

        $perusahaan = Perusahaan::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('logo')) {
            if ($perusahaan->logo) {
                Storage::delete('public/' . $perusahaan->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        $perusahaan->update($data);

        return redirect()->back()->with('success', 'Data perusahaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        if ($perusahaan->logo) {
            Storage::delete('public/' . $perusahaan->logo);
        }
        $perusahaan->delete();

        return redirect()->back()->with('success', 'Data perusahaan berhasil dihapus!');
    }
}