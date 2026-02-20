<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => 'required|min:6'
        ]);

        $reset = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return $this->htmlResponse(
                'error',
                '🔒',
                'Token Tidak Valid',
                'Token yang Anda gunakan tidak valid atau sudah kedaluwarsa.',
                'Silakan minta ulang tautan reset password.'
            );
        }

        $karyawan = Karyawan::where('email', $reset->email)->first();

        if (!$karyawan) {
            return $this->htmlResponse(
                'error',
                '🔍',
                'Pengguna Tidak Ditemukan',
                'Kami tidak menemukan akun yang terkait dengan permintaan ini.',
                'Pastikan email Anda sudah benar.'
            );
        }

        $karyawan->password = $request->password;
        $karyawan->save();

        DB::table('password_resets')->where('email', $reset->email)->delete();

        return $this->htmlResponse(
            'success',
            '✅',
            'Password Berhasil Diperbarui!',
            'Password akun Anda telah berhasil diubah. Silakan login menggunakan password baru Anda.',
            'Pastikan Anda menyimpan password baru di tempat yang aman.'
        );
    }

    private function htmlResponse($type, $icon, $title, $message, $hint)
    {
        $isSuccess = $type === 'success';

        // Warna disesuaikan dengan tema PT. Souci Indoprima
        $iconBg     = $isSuccess ? 'rgba(200,168,75,0.15)'  : 'rgba(220,53,69,0.1)';
        $iconBorder = $isSuccess ? '#c8a84b'                : '#dc3545';
        $badgeBg    = $isSuccess ? 'rgba(200,168,75,0.12)'  : 'rgba(220,53,69,0.08)';
        $badgeColor = $isSuccess ? '#a07830'                : '#b91c1c';
        $accentFrom = $isSuccess ? '#d9c99e'                : '#f87171';
        $accentMid  = $isSuccess ? '#c8a84b'                : '#dc3545';
        $accentTo   = $isSuccess ? '#5b9bd5'                : '#b91c1c';

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$title} – PT. Souci Indoprima</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
            <style>
                *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

                body {
                    font-family: 'Inter', sans-serif;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #1a2b4a;
                    background-image:
                        radial-gradient(ellipse at 20% 50%, rgba(44,90,160,0.4) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(91,155,213,0.2) 0%, transparent 55%);
                    padding: 20px;
                }

                .card {
                    width: 100%;
                    max-width: 420px;
                    background: #fff;
                    border-radius: 16px;
                    overflow: hidden;
                    box-shadow: 0 24px 60px rgba(0,0,0,0.35);
                    animation: up 0.5s cubic-bezier(0.22,1,0.36,1) both;
                }

                @keyframes up {
                    from { opacity: 0; transform: translateY(24px); }
                    to   { opacity: 1; transform: translateY(0); }
                }

                .card-header {
                    background: #ffffff;
                    padding: 28px 36px 20px;
                    text-align: center;
                    border-bottom: 1px solid #e8edf4;
                }

                .card-header img {
                    height: 60px;
                    width: auto;
                }

                .accent {
                    height: 3px;
                    background: linear-gradient(90deg, {$accentFrom}, {$accentMid}, {$accentTo});
                }

                .card-body {
                    padding: 32px 36px 36px;
                    text-align: center;
                }

                .icon-wrapper {
                    width: 80px;
                    height: 80px;
                    background: {$iconBg};
                    border: 2px solid {$iconBorder};
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 20px;
                    font-size: 34px;
                    animation: pulse 2s ease-in-out infinite;
                }

                @keyframes pulse {
                    0%, 100% { transform: scale(1); }
                    50%       { transform: scale(1.06); }
                }

                h2 {
                    font-family: 'Poppins', sans-serif;
                    font-size: 20px;
                    font-weight: 700;
                    color: #1a2b4a;
                    margin-bottom: 10px;
                }

                .message {
                    font-size: 14px;
                    color: #5a6a80;
                    line-height: 1.7;
                    margin-bottom: 20px;
                }

                .hint-badge {
                    display: inline-block;
                    background: {$badgeBg};
                    color: {$badgeColor};
                    font-family: 'Poppins', sans-serif;
                    font-size: 12px;
                    font-weight: 600;
                    padding: 10px 18px;
                    border-radius: 20px;
                    border: 1px solid {$iconBorder};
                }

                .divider {
                    height: 1px;
                    background: #e8edf4;
                    margin: 24px 0;
                }

                .footer-text {
                    font-size: 12px;
                    color: #b0bed4;
                }

                .footer-text span {
                    color: #2c5aa0;
                    font-weight: 600;
                }
            </style>
        </head>
        <body>
        <div class="card">

            <div class="card-header">
                <img src="/images/logo.png" alt="Logo PT. Souci Indoprima">
            </div>

            <div class="accent"></div>

            <div class="card-body">
                <div class="icon-wrapper">{$icon}</div>

                <h2>{$title}</h2>
                <p class="message">{$message}</p>

                <div class="hint-badge">💡 {$hint}</div>

                <div class="divider"></div>

                <p class="footer-text">&copy; 2025 <span>PT. Souci Indoprima</span>. All rights reserved.</p>
            </div>

        </div>
        </body>
        </html>
        HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
    }
}