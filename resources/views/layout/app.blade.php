<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Absensi Souci</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-600 text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Absensi Souci</h1>
                <p class="text-sm text-blue-200 mt-1">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 border-l-4 border-white' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.karyawan') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 {{ request()->routeIs('admin.karyawan') ? 'bg-blue-700 border-l-4 border-white' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Kelola Karyawan</span>
                </a>
                <a href="{{ route('admin.absensi') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 {{ request()->routeIs('admin.absensi') ? 'bg-blue-700 border-l-4 border-white' : '' }}">
                    <i class="fas fa-clipboard-check w-5"></i>
                    <span class="ml-3">Kelola Absensi</span>
                </a>
                <a href="{{ route('admin.laporan') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 {{ request()->routeIs('admin.laporan') ? 'bg-blue-700 border-l-4 border-white' : '' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3">Laporan</span>
                </a>
            </nav>

            <div class="absolute bottom-0 w-64 p-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center text-white hover:text-blue-200 w-full">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('header')</h2>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-2">{{ Auth::user()->name ?? 'Admin' }}</span>
                        <i class="fas fa-user-circle text-2xl text-gray-600"></i>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>