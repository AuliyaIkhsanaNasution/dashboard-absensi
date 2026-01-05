<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggan - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            <div class="text-center mb-8">
                <div class="mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="PT. Souci Indoprima" class="h-20 w-20 mx-auto object-contain">
                </div>
                <h1 class="text-xl font-bold text-gray-800 mb-1">LAPORAN PELANGGAN</h1>
                <p class="text-sm text-gray-600">Cek status laporan Anda</p>
            </div>

            <form action="#" method="GET">
                <div class="mb-4">
                    <label for="nomor_laporan" class="block text-sm font-medium text-gray-700 mb-2">Nomor Laporan</label>
                    <input 
                        type="text" 
                        id="nomor_laporan" 
                        name="nomor_laporan" 
                        placeholder="Masukkan nomor laporan"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200"
                >
                    Cek Laporan
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:text-blue-600 font-medium">
                        ← Kembali ke Login
                    </a>
                </div>
            </form>

        </div>
    </div>

</body>
</html>