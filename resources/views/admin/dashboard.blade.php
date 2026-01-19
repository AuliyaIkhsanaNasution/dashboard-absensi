<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">

    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" 
         @resize.window="if(window.innerWidth >= 768) sidebarOpen = true"
         class="flex h-screen overflow-hidden">
        
        <!-- Panggil Sidebar Component -->
        <x-sidebar />

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard Admin</h1>
                            <p class="text-sm text-gray-600 mt-1 hidden sm:block">Selamat datang di sistem absensi PT. Souci Indoprima</p>
                        </div>
                        
                        <!-- Hamburger Button untuk Mobile -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-4 sm:p-6 lg:p-8">
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
                    
                    <!-- Card 1: Total Karyawan -->
                    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-xs sm:text-sm font-medium">Total Karyawan</p>
                                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ $totalKaryawan }}</h3>
                            </div>
                            <div class="bg-blue-100 p-2 sm:p-3 rounded-full">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Hadir Hari Ini -->
                    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-xs sm:text-sm font-medium">Hadir Hari Ini</p>
                                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ $hadirHariIni }}</h3>
                            </div>
                            <div class="bg-green-100 p-2 sm:p-3 rounded-full">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Terlambat -->
                    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-xs sm:text-sm font-medium">Terlambat</p>
                                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ $terlambat }}</h3>
                            </div>
                            <div class="bg-yellow-100 p-2 sm:p-3 rounded-full">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Izin -->
                    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-xs sm:text-sm font-medium">Izin</p>
                                <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ $izin }}</h3>
                            </div>
                            <div class="bg-red-100 p-2 sm:p-3 rounded-full">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Quick Menu -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    
                    <!-- Menu Karyawan -->
                    <a href="{{ route('admin.karyawan') }}" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800">Data Karyawan</h3>
                        </div>
                        <p class="text-gray-600 text-xs sm:text-sm">Kelola data karyawan perusahaan</p>
                    </a>

                    <!-- Menu Absensi -->
                    <a href="{{ route('admin.absensi') }}" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800">Absensi</h3>
                        </div>
                        <p class="text-gray-600 text-xs sm:text-sm">Input dan monitor absensi harian</p>
                    </a>

                    <!-- Menu Laporan -->
                    <a href="{{ route('admin.laporan') }}" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition transform hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="bg-purple-100 p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800">Laporan</h3>
                        </div>
                        <p class="text-gray-600 text-xs sm:text-sm">Lihat laporan kehadiran lengkap</p>
                    </a>

                </div>

            </div>

        </main>

    </div>

</body>
</html>