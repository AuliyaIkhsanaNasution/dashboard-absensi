<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative">
    <!-- Background Image dengan Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/background.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-blue-900/50"></div> <!-- Overlay gelap -->
    </div>
    
    <!-- Content -->
    <div class="w-full max-w-md relative z-10">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            <!-- Logo & Title -->
            <div class="text-center mb-4">
                <div class="mb-4">
                    <!-- Logo akan ditampilkan di sini -->
                    <img src="{{ asset('images/logo.png') }}" alt="PT. Souci Indoprima" class="h-50 w-80 mx-auto object-contain">
                </div>
                <p class="text-sm text-gray-600">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Username Field -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Masukkan username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        required
                    >
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        required
                    >
                </div>

                <!-- Sign In Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200"
                >
                    Login
                </button>
            </form>

        </div>
    </div>

</body>
</html>