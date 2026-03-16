<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .stat-card {
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.08;
            background: currentColor;
        }

        .count-animate {
            animation: countUp 0.6s ease-out forwards;
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .menu-card {
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }
        .menu-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.12);
        }

        .logo-section {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">

    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" 
         @resize.window="if(window.innerWidth >= 768) sidebarOpen = true"
         class="flex h-screen overflow-hidden">
        
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            
            <!-- Header -->
            <header class="bg-white border-b border-gray-200">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard Admin</h1>
                            <p class="text-sm text-gray-500 mt-0.5 hidden sm:block">Selamat datang di sistem absensi PT. Souci Indoprima</p>
                        </div>
                        
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8">

                <!-- Statistics Cards — div biasa, tidak bisa diklik -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-1">

                    <!-- Card 1: Total Laporan Harian -->
                    <div class="stat-card bg-white rounded-2xl shadow-sm p-5 sm:p-6 flex items-start justify-between text-green-500">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">Total Laporan Harian</p>
                            <h3 class="text-2xl sm:text-3xl text-gray-800 count-animate flex items-end gap-1">
                            <span class="font-extrabold">{{ $hadirHariIni }}</span>
                            <span class="font-normal text-sm sm:text-base text-gray-600">Laporan</span>
                        </h3>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Absensi hari ini
                            </p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-xl flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Card 2: Total Karyawan -->
                    <div class="stat-card bg-white rounded-2xl shadow-sm p-5 sm:p-6 flex items-start justify-between text-blue-500">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">Total Karyawan</p>
                            <h3 class="text-2xl sm:text-3xl text-gray-800 count-animate flex items-end gap-1">
                                <span class="font-extrabold">{{ $totalKaryawan }}</span>
                                <span class="font-normal text-sm sm:text-base text-gray-600">Karyawan</span>
                            </h3>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                Total seluruh karyawan
                            </p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-xl flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Card 3: Total Unit Kerja -->
                    <div class="stat-card bg-white rounded-2xl shadow-sm p-5 sm:p-6 flex items-start justify-between text-red-500">
                        <div>
                            <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">Total Unit Kerja</p>
                            <h3 class="text-2xl sm:text-3xl text-gray-800 count-animate flex items-end gap-1">
                                <span class="font-extrabold">{{ $totalPerusahaan }}</span>
                                <span class="font-normal text-sm sm:text-base text-gray-600">Unit Kerja</span>
                            </h3>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                </svg>
                                Total seluruh unit kerja
                            </p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-xl flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>

                </div>

                <!-- Logo Section -->
                <div class="logo-section bg-white rounded-2xl shadow-sm p-8 sm:p-12 mb-8 flex flex-col items-center justify-center">
                    <img 
                        src="{{ asset('images/logo.png') }}" 
                        alt="PT. Souci Indoprima" 
                        class="h-20 sm:h-28 object-contain mb-1"
                    >

                    <p class="text-sm text-gray-400 text-center mb-6">
                        Sistem Informasi Absensi Karyawan
                    </p>

                    <p class="text-lg text-gray-600 text-justify w-full leading-relaxed">
                        Absensi Souci adalah sebuah sistem informasi absensi karyawan berbasis web yang dirancang untuk memantau dan mengelola kehadiran seluruh karyawan PT. Souci Indoprima secara real-time. Sistem ini dikembangkan untuk membantu perusahaan dalam meningkatkan kedisiplinan, transparansi, serta efisiensi dalam proses pencatatan kehadiran karyawan di setiap unit kerja.
                    </p>

                    <p class="text-lg text-gray-600 text-justify w-full leading-relaxed mt-3">
                        Melalui sistem ini, admin dapat dengan mudah melihat laporan kehadiran harian, mengelola data karyawan, serta memantau aktivitas absensi di seluruh unit kerja perusahaan. Dengan dukungan teknologi berbasis web, Absensi Souci memberikan kemudahan akses, pengelolaan data yang terpusat, serta membantu manajemen dalam mengambil keputusan yang lebih cepat dan akurat terkait kehadiran karyawan.
                    </p>
                </div>

                

            </div>

        </main>

    </div>

</body>
</html>