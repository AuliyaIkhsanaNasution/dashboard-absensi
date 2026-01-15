<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Absensi API siap digunakan'
        ]);
    }
}
