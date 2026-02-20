<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100" 
      x-data="{ 
          sidebarOpen: window.innerWidth >= 768
      }"
      @resize.window="if(window.innerWidth >= 768) sidebarOpen = true">

    <div class="flex h-screen overflow-hidden">
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            <!-- Header with Hamburger -->
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Rekap Kehadiran</h1>
                            <p class="text-sm text-gray-600 mt-1 hidden sm:block">Rekap data kehadiran berdasarkan absensi dan izin yang tercatat</p>
                        </div>
                        
                        <!-- Hamburger Button -->
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
                <!-- Filter Bulan, Tahun & Perusahaan -->
<div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
    <form method="GET" action="{{ route('admin.rekap') }}" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Perusahaan</label>
            <select name="perusahaan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Perusahaan</option>
                @foreach($perusahaans as $p)
                    <option value="{{ $p->id }}" {{ $perusahaanId == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_pt }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
            <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="01" {{ $bulan == '01' ? 'selected' : '' }}>Januari</option>
                <option value="02" {{ $bulan == '02' ? 'selected' : '' }}>Februari</option>
                <option value="03" {{ $bulan == '03' ? 'selected' : '' }}>Maret</option>
                <option value="04" {{ $bulan == '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ $bulan == '05' ? 'selected' : '' }}>Mei</option>
                <option value="06" {{ $bulan == '06' ? 'selected' : '' }}>Juni</option>
                <option value="07" {{ $bulan == '07' ? 'selected' : '' }}>Juli</option>
                <option value="08" {{ $bulan == '08' ? 'selected' : '' }}>Agustus</option>
                <option value="09" {{ $bulan == '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ $bulan == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ $bulan == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ $bulan == '12' ? 'selected' : '' }}>Desember</option>
            </select>
        </div>
        
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
            <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        
        <div>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
        </div>
    </form>
    
    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex flex-wrap items-center gap-4 text-sm">
            @if($perusahaanId)
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-gray-700">
                        <strong>Perusahaan:</strong> 
                        {{ $perusahaans->firstWhere('id', $perusahaanId)->nama_pt ?? 'Semua' }}
                    </span>
                </div>
            @endif
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-700"><strong>Jumlah Hari:</strong> {{ $jumlahHariKerja }} hari</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-gray-700"><strong>Periode:</strong> {{ $periodeText }}</span>
            </div>
        </div>
    </div>
</div>
                <!-- Statistik Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Tepat Waktu</p>
                                <p class="text-3xl font-bold mt-2">{{ $totalData['tepat_waktu'] }}</p>
                                <p class="text-green-100 text-xs mt-1">Total kehadiran tepat waktu</p>
                            </div>
                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-md p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium">Terlambat</p>
                                <p class="text-3xl font-bold mt-2">{{ $totalData['terlambat'] }}</p>
                                <p class="text-yellow-100 text-xs mt-1">Total keterlambatan</p>
                            </div>
                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Izin</p>
                                <p class="text-3xl font-bold mt-2">{{ $totalData['izin'] }}</p>
                                <p class="text-blue-100 text-xs mt-1">Total karyawan izin</p>
                            </div>
                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-medium">Tidak Hadir</p>
                                <p class="text-3xl font-bold mt-2">{{ $totalData['tidak_hadir'] }}</p>
                                <p class="text-red-100 text-xs mt-1">Total ketidakhadiran</p>
                            </div>
                            <div class="bg-white bg-opacity-30 rounded-full p-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Rekap -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800">Detail Rekap Per Karyawan</h2>
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NIP</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Karyawan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Perusahaan</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Tepat Waktu</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Terlambat</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Izin</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Tidak Hadir</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($karyawan as $index => $k)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $k['nip'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $k['nama'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $k['jabatan'] ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $k['perusahaan'] }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            {{ $k['tepat_waktu'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                            {{ $k['terlambat'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                            {{ $k['izin'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                            {{ $k['tidak_hadir'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center">
                                            <div class="w-full max-w-[120px]">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                                        <div class="h-2.5 rounded-full transition-all duration-300 
                                                            @if($k['persentase_kehadiran'] >= 80) bg-green-500
                                                            @elseif($k['persentase_kehadiran'] >= 60) bg-yellow-500
                                                            @else bg-red-500
                                                            @endif" 
                                                            style="width: {{ $k['persentase_kehadiran'] }}%">
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-bold text-gray-700 min-w-[45px] text-right">{{ $k['persentase_kehadiran'] }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Data</h3>
                                            <p class="text-gray-500">Belum ada data karyawan yang tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden divide-y divide-gray-200">
                        @forelse($karyawan as $index => $k)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-800 mb-1">{{ $k['nama'] }}</h3>
                                    <p class="text-xs text-gray-500">{{ $k['nip'] }} • {{ $k['jabatan'] ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold 
                                        @if($k['persentase_kehadiran'] >= 80) text-green-600
                                        @elseif($k['persentase_kehadiran'] >= 60) text-yellow-600
                                        @else text-red-600
                                        @endif">
                                        {{ $k['persentase_kehadiran'] }}%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-4 gap-2">
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">Tepat</div>
                                    <div class="text-sm font-bold text-green-600">{{ $k['tepat_waktu'] }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">Terlambat</div>
                                    <div class="text-sm font-bold text-yellow-600">{{ $k['terlambat'] }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">Izin</div>
                                    <div class="text-sm font-bold text-blue-600">{{ $k['izin'] }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">Alpa</div>
                                    <div class="text-sm font-bold text-red-600">{{ $k['tidak_hadir'] }}</div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-10 text-center text-gray-500">
                            <p class="text-base">Belum ada data karyawan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-blue-900 mb-1">Informasi Perhitungan</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• <strong>Tepat Waktu:</strong> Karyawan yang hadir sesuai atau sebelum batas waktu shift (termasuk toleransi)</li>
                                <li>• <strong>Terlambat:</strong> Karyawan yang hadir melebihi batas waktu shift + toleransi</li>
                                <li>• <strong>Izin:</strong> Karyawan yang mengajukan izin pada tanggal tersebut</li>
                                <li>• <strong>Tidak Hadir:</strong> Karyawan yang tidak absen dan tidak izin</li>
                                <li>• <strong>Persentase Kehadiran:</strong> (Tepat Waktu + Terlambat + Izin) / Total Hari × 100%</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ 
                icon: 'success', 
                title: 'Berhasil', 
                text: "{{ session('success') }}", 
                timer: 3000, 
                showConfirmButton: false 
            });
        @endif
    </script>
</body>
</html>