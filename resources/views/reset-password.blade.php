<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – PT. Souci Indoprima</title>
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

        .accent { height: 3px; background: linear-gradient(90deg, #d9c99e, #c8a84b, #5b9bd5); }

        .card-body { padding: 32px 36px 36px; }

        h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 6px;
            text-align: center;
        }

        .subtitle {
            font-size: 13px;
            color: #8a9ab5;
            text-align: center;
            margin-bottom: 28px;
        }

        label {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 11.5px;
            font-weight: 600;
            color: #1a2b4a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; }

        .input-wrap svg.lock {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #b0bed4;
            pointer-events: none;
        }

        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 13px 44px;
            border: 1.5px solid #e8edf4;
            border-radius: 10px;
            font-size: 14px;
            color: #1e2d45;
            background: #f5f7fa;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        input:focus {
            border-color: #2c5aa0;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(44,90,160,0.1);
        }

        .toggle-btn {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: #b0bed4;
            display: flex; align-items: center;
            transition: color 0.2s;
        }
        .toggle-btn:hover { color: #2c5aa0; }

        .btn {
            display: block;
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            background: linear-gradient(135deg, #2c5aa0, #1a4a8a);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(44,90,160,0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(44,90,160,0.4); }
        .btn:active { transform: none; }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
            font-size: 13px;
            color: #2c5aa0;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover { color: #1a2b4a; }
    </style>
</head>
<body>
<div class="card">

    <!-- Header dengan logo asli -->
    <div class="card-header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
    </div>

    <div class="accent"></div>

    <div class="card-body">
        <h2>Reset Password</h2>
        <p class="subtitle">Masukkan password baru Anda di bawah ini.</p>

        <form method="POST" action="{{ url('/reset-password') }}">
            @csrf
            <input type="hidden" name="token" value="{{ request('token') }}">

            <label for="password">Password Baru</label>
            <div class="input-wrap">
                <svg class="lock" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input type="password" id="password" name="password" placeholder="Masukkan password baru" required>
                <button type="button" class="toggle-btn" onclick="toggle()">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>

            <button type="submit" class="btn">Ganti Password</button>
        </form>
    </div>
</div>

<script>
    function toggle() {
        const inp = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        const show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        icon.innerHTML = show
            ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
</script>
</body>
</html>