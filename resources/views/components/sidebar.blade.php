<!-- resources/views/components/sidebar.blade.php -->

<!-- Overlay untuk mobile (klik untuk tutup sidebar) -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 md:hidden">
</div>

<!-- Sidebar -->
<aside x-show="sidebarOpen"
       @click.away="if(window.innerWidth < 768) sidebarOpen = false"
       x-transition:enter="transition ease-in-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in-out duration-300 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="fixed md:static inset-y-0 left-0 z-50 w-64 md:w-52 bg-white shadow-lg flex flex-col">
    
    <!-- Logo & Company Name -->
    <div class="p-6 border-b">
        <div class="flex items-center justify-between">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
            <!-- Tombol Close untuk Mobile -->
            <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
    
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('admin.karyawan') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.karyawan') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="font-medium">Data Karyawan</span>
        </a>

        <a href="{{ route('admin.perusahaan') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.perusahaan') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span class="font-medium">Data Perusahaan</span>
        </a>

        <a href="{{ route('admin.absensi') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.absensi') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span class="font-medium">Absensi</span>
        </a>

        <a href="{{ route('admin.izin') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.izin') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
            <span class="font-medium">Izin</span>
        </a>

        <a href="{{ route('admin.cuti') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.cuti') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="font-medium">Cuti</span>
        </a>

        <a href="{{ route('admin.lembur') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.lembur') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Lembur</span>
        </a>

        <a href="{{ route('admin.shift') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.shift') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 0l1 1"></path>
            </svg>
            <span class="font-medium">Shift</span>
        </a>

        <a href="{{ route('admin.rekap') }}" 
           class="flex items-center px-4 py-3 text-gray-700 rounded transition
                  {{ request()->routeIs('admin.rekap') ? 'bg-blue-50 border-l-4 border-blue-500 rounded-r' : 'hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="font-medium">Rekap</span>
        </a>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>