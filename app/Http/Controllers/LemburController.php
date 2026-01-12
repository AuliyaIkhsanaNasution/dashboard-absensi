<?php

namespace App\Http\Controllers;

use App\Models\Lembur;
use Illuminate\Http\Request;

class LemburController extends Controller
{
    public function index()
{
    // Mengambil semua data lembur beserta relasi karyawan
    $allLembur = \App\Models\Lembur::with('karyawan')->latest()->get();
    
    // Mengambil daftar karyawan untuk pilihan di modal "Tambah Lembur"
    $karyawans = \App\Models\Karyawan::all();

    return view('admin.lembur', compact('allLembur', 'karyawans'));
}
}