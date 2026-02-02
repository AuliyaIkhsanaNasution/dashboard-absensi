<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" 
      x-data="{ sidebarOpen: window.innerWidth >= 768, ...absensiApp() }" 
      @resize.window="if(window.innerWidth >= 768) sidebarOpen = true">

    <div class="flex h-screen overflow-hidden">
        
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            
            <!-- Header with Hamburger -->
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Rekapitulasi Absensi</h1>
                            <p class="text-sm text-gray-600 mt-1 hidden sm:block">Data absensi karyawan per tanggal {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
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

            @if($errors->any())
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="p-4 sm:p-6 lg:p-8">
                
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
                    <div class="flex flex-col gap-4">
                        
                        <!-- Search Box -->
                        <div class="w-full">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @input="filterAbsensi"
                                    placeholder="Cari nama karyawan..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Filters and Actions -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1">
                                <input 
                                    type="date"
                                    x-model="selectedDate"
                                    @change="filterByDate"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                >
                            </div>
                            
                            <select 
                                x-model="filterStatus"
                                @change="filterAbsensi"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="Tepat Waktu">Tepat Waktu</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                            
                            <button @click="openAdd = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg flex items-center justify-center gap-2 transition text-sm whitespace-nowrap">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <span class="hidden sm:inline">Input Absen Manual</span>
                                <span class="sm:hidden">Tambah</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Karyawan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <template x-for="absen in filteredAbsensi" :key="absen.id">
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                    <template x-if="absen.karyawan.foto">
                                                        <img :src="absen.karyawan.foto" :alt="absen.karyawan.nama" class="w-full h-full object-cover">
                                                    </template>
                                                    <template x-if="!absen.karyawan.foto">
                                                        <span class="text-blue-600 font-bold text-sm" x-text="absen.karyawan.nama.charAt(0)"></span>
                                                    </template>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800" x-text="absen.karyawan.nama"></p>
                                                    <p class="text-xs text-gray-500" x-text="absen.karyawan.jabatan"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="formatTanggal(absen.tanggal)"></td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium" 
                                                :class="{
                                                    'bg-green-100 text-green-800': absen.status == 'Tepat Waktu',
                                                    'bg-orange-100 text-orange-800': absen.status == 'Terlambat'
                                                }"
                                                x-text="absen.status"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <button @click="selectedAbsensi = absen; openView = true" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition" title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <button 
                                                    @click="selectedAbsensi = Object.assign({}, absen); openEdit = true" 
                                                    class="text-yellow-500 hover:bg-yellow-50 p-2 rounded-lg transition" 
                                                    title="Edit Data">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <form :id="'delete-form-' + absen.id" :action="'/admin/absensi/' + absen.id" method="POST">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button 
                                                        type="button" 
                                                        @click="handleDelete(absen.id)" 
                                                        class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition"
                                                        title="Hapus Data">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-if="filteredAbsensi.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                <p class="text-lg font-medium">Tidak ada data absensi</p>
                                                <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau kata kunci pencarian</p>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden divide-y divide-gray-200">
                        <template x-for="absen in filteredAbsensi" :key="absen.id">
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-3 mb-3">
                                    <!-- Photo -->
                                    <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <template x-if="absen.karyawan.foto">
                                            <img :src="absen.karyawan.foto" :alt="absen.karyawan.nama" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!absen.karyawan.foto">
                                            <span class="text-blue-600 font-bold text-sm" x-text="absen.karyawan.nama.charAt(0)"></span>
                                        </template>
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="absen.karyawan.nama"></p>
                                        <p class="text-xs text-gray-500" x-text="absen.karyawan.jabatan"></p>
                                        <div class="mt-2 flex items-center gap-2 flex-wrap">
                                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                                                :class="{
                                                    'bg-green-100 text-green-800': absen.status == 'Tepat Waktu',
                                                    'bg-orange-100 text-orange-800': absen.status == 'Terlambat'
                                                }"
                                                x-text="absen.status"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Date Info -->
                                <div class="mb-3 text-xs bg-gray-50 rounded-lg p-2">
                                    <p class="text-gray-500">Tanggal</p>
                                    <p class="font-semibold text-gray-800" x-text="formatTanggal(absen.tanggal)"></p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button 
                                        @click="selectedAbsensi = absen; openView = true" 
                                        class="flex-1 bg-blue-50 text-blue-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                                        Lihat Detail
                                    </button>
                                    <button 
                                        @click="selectedAbsensi = Object.assign({}, absen); openEdit = true" 
                                        class="flex-1 bg-yellow-50 text-yellow-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-yellow-100 transition">
                                        Edit
                                    </button>
                                    <form :id="'delete-form-' + absen.id" :action="'/admin/absensi/' + absen.id" method="POST">
                                        @csrf 
                                        @method('DELETE')
                                        <button 
                                            type="button" 
                                            @click="handleDelete(absen.id)" 
                                            class="bg-red-50 text-red-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="filteredAbsensi.length === 0">
                            <div class="p-10 text-center text-gray-500">
                                <svg class="w-16 h-16 text-gray-300 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-base font-medium">Tidak ada data absensi</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau kata kunci</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Tambah (Input Manual) --}}
    <div x-show="openAdd" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60" @click="openAdd = false"></div>
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-md p-4 sm:p-6 z-50 relative max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg sm:text-xl font-bold mb-4 text-gray-800">Input Absen Manual</h3>
                
                <form action="{{ route('admin.absensi.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Karyawan</label>
                            <div class="relative" x-data="{ open: false, search: '' }">
                                <input 
                                    type="text" 
                                    x-model="search"
                                    @click="open = true"
                                    @click.away="open = false"
                                    placeholder="Ketik untuk mencari karyawan..."
                                    class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                    autocomplete="off">
                                
                                <input type="hidden" name="karyawan_id" x-model="selectedKaryawanId">
                                
                                <div x-show="open" 
                                     class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                     x-cloak>
                                    <template x-for="k in karyawans.filter(item => 
                                        item.nama.toLowerCase().includes(search.toLowerCase()) || 
                                        item.nip.toLowerCase().includes(search.toLowerCase())
                                    )" :key="k.id">
                                        <div 
                                            @click="selectedKaryawanId = k.id; search = k.nama; open = false"
                                            class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 transition">
                                            <p class="text-sm font-medium text-gray-800" x-text="k.nama"></p>
                                            <p class="text-xs text-gray-500" x-text="k.nip + ' - ' + k.jabatan"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                                    <select
                                    name="shift_id"
                                    class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                    required
                                    >
                                    <option value="">Pilih Shift</option>
                                    @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">
                                    {{ $shift->nama_shift }}
                                    ({{ substr($shift->jam_masuk, 0, 5) }} - {{ substr($shift->jam_pulang, 0, 5) }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                            <select name="status" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm" required>
                                <option value="Tepat Waktu">Tepat Waktu</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pulang</label>
                                <input type="time" name="jam_pulang" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openAdd = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition text-sm">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition text-sm">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="openEdit" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60" @click="openEdit = false"></div>
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-md p-4 sm:p-6 z-50 relative max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg sm:text-xl font-bold mb-4 text-gray-800">Edit Data Absensi</h3>
                
                <form :action="'/admin/absensi/' + selectedAbsensi.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="p-3 bg-gray-50 rounded-lg flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold" x-text="selectedAbsensi.karyawan?.nama?.charAt(0)"></div>
                            <div>
                                <p class="text-sm font-bold text-gray-800" x-text="selectedAbsensi.karyawan?.nama"></p>
                                <p class="text-xs text-gray-500" x-text="selectedAbsensi.karyawan?.jabatan"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" x-model="selectedAbsensi.tanggal" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm" required>
                            </div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
<select
name="shift_id"
x-model="selectedAbsensi.shift_id"
class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm"
required
>
<option value="">Pilih Shift</option>
@foreach ($shifts as $shift)
<option value="{{ $shift->id }}">
{{ $shift->nama_shift }}
({{ substr($shift->jam_masuk, 0, 5) }} - {{ substr($shift->jam_pulang, 0, 5) }})
</option>
@endforeach
</select>
</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                            <select name="status" x-model="selectedAbsensi.status" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm" required>
                                <option value="Tepat Waktu">Tepat Waktu</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk</label>
                                <input type="time" name="jam_masuk" x-model="selectedAbsensi.jam_masuk" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pulang</label>
                                <input type="time" name="jam_pulang" x-model="selectedAbsensi.jam_pulang" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition text-sm">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition text-sm">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal View Detail --}}
    <div x-show="openView" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/60 transition-opacity" @click="openView = false"></div>
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-4xl p-4 sm:p-6 z-50 relative max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4 sm:mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Detail Absensi</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            <span x-text="selectedAbsensi.karyawan?.nama"></span> - 
                            <span x-text="selectedAbsensi.tanggal"></span>
                        </p>
                    </div>
                    <button @click="openView = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition">&times;</button>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
                    <!-- Jam Masuk Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="bg-green-500 rounded-lg p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-green-700 uppercase">Jam Masuk</p>
                                <p class="text-xl sm:text-2xl font-bold text-green-900" x-text="selectedAbsensi.jam_masuk || '--:--'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Jam Pulang Card -->
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                        <div class="flex items-center gap-3">
                            <div class="bg-orange-500 rounded-lg p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-orange-700 uppercase">Jam Pulang</p>
                                <p class="text-xl sm:text-2xl font-bold text-orange-900" x-text="selectedAbsensi.jam_pulang || '--:--'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Kerja Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-500 rounded-lg p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-blue-700 uppercase">Total Kerja</p>
                                <p class="text-xl sm:text-2xl font-bold text-blue-900" x-text="selectedAbsensi.total_kerja || '0 Jam'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photos Section -->
                <div class="mb-4">
                    <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase">Bukti Foto Absensi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Foto Masuk -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <p class="text-xs sm:text-sm font-bold text-gray-600 uppercase">Foto Masuk</p>
                            </div>
                            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 flex items-center justify-center shadow-inner">
                                <template x-if="selectedAbsensi.foto_masuk">
                                    <img :src="'/storage/' + selectedAbsensi.foto_masuk" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </template>
                                <template x-if="!selectedAbsensi.foto_masuk">
                                    <div class="text-center p-4">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-400 italic text-xs sm:text-sm">Belum ada foto masuk</span>
                                    </div>
                                </template>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <template x-if="selectedAbsensi.latitude && selectedAbsensi.longitude">
                                    <span x-text="`Lat: ${selectedAbsensi.latitude}, Lng: ${selectedAbsensi.longitude}`"></span>
                                </template>
                                <template x-if="!selectedAbsensi.latitude || !selectedAbsensi.longitude">
                                    <span>Lokasi tidak tersedia</span>
                                </template>
                            </p>
                        </div>

                        <!-- Foto Pulang -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                <p class="text-xs sm:text-sm font-bold text-gray-600 uppercase">Foto Pulang</p>
                            </div>
                            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 flex items-center justify-center shadow-inner">
                                <template x-if="selectedAbsensi.foto_keluar">
                                    <img :src="'/storage/' + selectedAbsensi.foto_keluar" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </template>
                                <template x-if="!selectedAbsensi.foto_keluar">
                                    <div class="text-center p-4">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-400 italic text-xs sm:text-sm">Belum ada foto pulang</span>
                                    </div>
                                </template>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <template x-if="selectedAbsensi.latitude && selectedAbsensi.longitude">
                                    <span x-text="`Lat: ${selectedAbsensi.latitude}, Lng: ${selectedAbsensi.longitude}`"></span>
                                </template>
                                <template x-if="!selectedAbsensi.latitude || !selectedAbsensi.longitude">
                                    <span>Lokasi tidak tersedia</span>
                                </template>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-gray-50 rounded-lg p-4 mt-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs sm:text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Shift</p>

                            <p class="font-semibold text-gray-800">
                                <template x-if="selectedAbsensi.shift">
                                    <span
                                        x-text="`${selectedAbsensi.shift.nama_shift} 
                                        (${selectedAbsensi.shift.jam_masuk.slice(0,5)} - 
                                        ${selectedAbsensi.shift.jam_pulang.slice(0,5)})`">
                                    </span>
                                </template>

                                <template x-if="!selectedAbsensi.shift">
                                    <span>-</span>
                                </template>
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Status</p>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                                :class="{
                                    'bg-green-100 text-green-800': selectedAbsensi.status == 'Tepat Waktu',
                                    'bg-orange-100 text-orange-800': selectedAbsensi.status == 'Terlambat'
                                }"
                                x-text="selectedAbsensi.status"></span>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">NIP</p>
                            <p class="font-semibold text-gray-800" x-text="selectedAbsensi.karyawan?.nip || '-'"></p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Jabatan</p>
                            <p class="font-semibold text-gray-800" x-text="selectedAbsensi.karyawan?.jabatan || '-'"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style> 
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function absensiApp() {
            return {
                openAdd: false,
                openEdit: false,
                openView: false,
                selectedAbsensi: {},
                selectedKaryawanId: '',
                searchQuery: '',
                filterStatus: '',
                selectedDate: '{{ $tanggal ?? date("Y-m-d") }}',
                allAbsensi: @json($absensiHariIni),
                karyawans: @json($karyawans),
                filteredAbsensi: [],

                init() {
                    this.filteredAbsensi = this.allAbsensi;
                },

                filterByDate() {
                    // Redirect ke URL dengan parameter tanggal
                    window.location.href = '{{ route("admin.absensi") }}?tanggal=' + this.selectedDate;
                },

                filterAbsensi() {
                    let result = this.allAbsensi;

                    if (this.searchQuery.trim() !== '') {
                        const query = this.searchQuery.toLowerCase();
                        result = result.filter(a =>
                            a.karyawan.nama.toLowerCase().includes(query) ||
                            a.karyawan.jabatan.toLowerCase().includes(query) ||
                            a.karyawan.nip.toLowerCase().includes(query)
                        );
                    }

                    if (this.filterStatus !== '') {
                        result = result.filter(a => a.status === this.filterStatus);
                    }

                    this.filteredAbsensi = result;
                },

                formatTanggal(tanggal) {
                    const date = new Date(tanggal);
                    const options = { day: '2-digit', month: 'short', year: 'numeric' };
                    return date.toLocaleDateString('id-ID', options);
                },

                handleDelete(id) {
                    Swal.fire({
                        title: 'Hapus data absensi ini?',
                        text: "Data absensi akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    });
                }
            }
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    </script>
</body>
</html>