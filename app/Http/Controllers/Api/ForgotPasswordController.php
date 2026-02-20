<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // STEP 1: user masukkan email di aplikasi absensi
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $karyawan = Karyawan::where('email', $request->email)->first();

        if (!$karyawan) {
            return response()->json(['message' => 'Email tidak terdaftar'], 404);
        }

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        $link = url("/reset-password?token=".$token);

        Mail::raw("Klik link reset password: $link", function ($msg) use ($request) {
            $msg->to($request->email)
                ->subject('Reset Password Akun Absensi');
        });

        return response()->json(['message' => 'Email reset password telah dikirim!']);
    }


    // // STEP 2: halaman web reset password submit password baru
    // public function resetPassword(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required',
    //         'password' => 'required|min:6'
    //     ]);

    //     $reset = DB::table('password_resets')->where('token', $request->token)->first();

    //     if (!$reset) {
    //         return response()->json(['message' => 'Token tidak valid'], 400);
    //     }

    //     $karyawan = Karyawan::where('email', $reset->email)->first();

    //     if (!$karyawan) {
    //         return response()->json(['message' => 'User tidak ditemukan'], 404);
    //     }

    //     $karyawan->password = $request->password; // otomatis hash oleh mutator
    //     $karyawan->save();

    //     DB::table('password_resets')->where('email', $reset->email)->delete();

    //     return response()->json(['message' => 'Password berhasil diganti!']);
    // }
}
