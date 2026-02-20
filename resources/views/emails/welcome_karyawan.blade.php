<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Bergabung</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        .email-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="rgba(255,255,255,0.1)"/></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        .header-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .header-icon svg {
            width: 40px;
            height: 40px;
            fill: #667eea;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .header p {
            font-size: 18px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .greeting strong {
            color: #667eea;
            font-size: 24px;
            display: block;
            margin-top: 8px;
        }
        .welcome-message {
            color: #555;
            line-height: 1.8;
            margin-bottom: 30px;
            text-align: center;
            font-size: 16px;
        }
        .credentials-box {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        .credentials-box::before {
            content: '🔐';
            position: absolute;
            top: -20px;
            right: -20px;
            font-size: 120px;
            opacity: 0.1;
        }
        .credentials-box h3 {
            color: #333;
            margin-bottom: 25px;
            font-size: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .credential-item {
            background: white;
            margin-bottom: 15px;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .credential-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-weight: 600;
            color: #667eea;
            font-size: 13px;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .credential-value {
            font-size: 18px;
            color: #333;
            font-weight: 700;
            letter-spacing: 0.5px;
            word-break: break-all;
        }
        .warning-box {
            background: linear-gradient(135deg, #fff4e6 0%, #ffe8cc 100%);
            border-left: 4px solid #ff9800;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.1);
        }
        .warning-box strong {
            color: #e65100;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 16px;
        }
        .warning-box ul {
            margin: 10px 0;
            padding-left: 25px;
            color: #bf360c;
        }
        .warning-box li {
            margin: 8px 0;
            line-height: 1.6;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn-login {
            display: inline-block;
            padding: 16px 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 30px 0;
        }
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .footer-logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: white;
        }
        .footer p {
            margin: 8px 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            margin: 0 8px;
            line-height: 40px;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-3px);
        }
        .company-info {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 13px;
            line-height: 1.6;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            .email-container {
                border-radius: 12px;
            }
            .header {
                padding: 40px 20px;
            }
            .header h1 {
                font-size: 26px;
            }
            .content {
                padding: 30px 20px;
            }
            .credentials-box {
                padding: 20px;
            }
            .credential-value {
                font-size: 16px;
            }
            .btn-login {
                padding: 14px 35px;
                font-size: 14px;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .content > * {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h1>🎉 Selamat Bergabung!</h1>
                <p>PT. Souci Indoprima</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p class="greeting">
                    Halo,
                    <strong>{{ $karyawan->nama }}</strong>
                </p>

                <p class="welcome-message">
                    Kami sangat senang menyambut Anda sebagai bagian dari keluarga besar <strong>PT. Souci Indoprima</strong>. 
                    Selamat datang di tim kami! 🚀
                </p>

                <div class="divider"></div>

                <!-- Credentials Box -->
                <div class="credentials-box">
                    <h3>
                        <span>🔐</span>
                        Informasi Akun Anda
                    </h3>

                    <div class="credential-item">
                        <span class="credential-label">📧 Email Login</span>
                        <div class="credential-value">{{ $karyawan->email }}</div>
                    </div>

                    <div class="credential-item">
                        <span class="credential-label">🆔 NIP (Nomor Induk Pegawai)</span>
                        <div class="credential-value">{{ $karyawan->nip }}</div>
                    </div>

                    <div class="credential-item">
                        <span class="credential-label">🔑 Password</span>
                        <div class="credential-value">{{ $password }}</div>
                    </div>
                </div>

                <!-- Warning Box -->
                <div class="warning-box">
                    <strong>
                        <span>⚠️</span>
                        Penting untuk Keamanan Akun Anda!
                    </strong>
                    <ul>
                        <li><strong>Simpan</strong> informasi login ini dengan aman</li>
                        <li><strong>Jangan bagikan</strong> password kepada siapapun</li>
                        <li><strong>Segera ganti</strong> password setelah login pertama kali</li>
                        <li><strong>Gunakan password</strong> yang kuat dan unik</li>
                    </ul>
                </div>

                <div class="divider"></div>

                <p style="color: #666; line-height: 1.8; text-align: center; font-size: 15px;">
                    Jika Anda mengalami kesulitan dalam mengakses sistem atau memiliki pertanyaan, 
                    jangan ragu untuk menghubungi <strong>Tim IT</strong> atau <strong>HRD</strong> kami.
                </p>

                <div style="margin-top: 30px; text-align: center;">
                    <p style="color: #555; line-height: 1.6; font-size: 15px;">
                        Hormat kami,<br>
                        <strong style="color: #667eea; font-size: 17px;">Tim HRD PT. Souci Indoprima</strong>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-logo">PT. SOUCI INDOPRIMA</div>
                
                <div class="company-info">
                    📍 Jl. Sei Serayu No.87, Babura Sunggal, Kec. Medan Sunggal, Kota Medan, Sumatera Utara 20154<br>
                    📞 Telp: (061) 4533869<br>
                    📧 Email: souciindoprima@gmail.com
                </div>

                <p style="margin-top: 20px; font-size: 13px; opacity: 0.8;">
                    Email ini dikirim secara otomatis oleh sistem.<br>
                    Mohon tidak membalas email ini.
                </p>
                
                <p style="margin-top: 15px; font-size: 12px; opacity: 0.7;">
                    &copy; {{ date('Y') }} PT. Souci Indoprima. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>