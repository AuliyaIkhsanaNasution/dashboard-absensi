<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index()
    {
        // Mengambil semua data izin beserta data karyawan terkait (Eager Loading)
        $allIzin = Izin::with('karyawan')->latest()->get();

        // Mengirimkan variabel $allIzin ke view
        return view('admin.izin', compact('allIzin'));
    }
}