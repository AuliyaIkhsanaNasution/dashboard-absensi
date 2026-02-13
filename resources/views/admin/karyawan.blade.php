<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
        * { scrollbar-width: thin; scrollbar-color: #888 #f1f1f1; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: window.innerWidth >= 768, ...karyawanApp() }" 
      @resize.window="if(window.innerWidth >= 768) sidebarOpen = true">

    <div class="flex h-screen overflow-hidden">
        
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            
            <!-- Header with Hamburger -->
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Data Karyawan</h1>
                            <p class="text-sm text-gray-600 mt-1 hidden sm:block">Kelola data karyawan PT. Souci Indoprima</p>
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
            <div class="mx-4 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="p-4 sm:p-6 lg:p-8">
    
    <!-- Header dengan Tombol (SAMA seperti Perusahaan) -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
                <h2 class="text-base sm:text-lg font-semibold text-gray-700">Daftar Karyawan</h2>
            </div>
            <div class="flex gap-3">
                <button @click="openAdd = true" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 sm:px-6 py-2 rounded-lg flex items-center justify-center gap-2 transition text-sm sm:text-base whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span class="hidden sm:inline">Tambah Karyawan</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section (Box Terpisah) -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col gap-4">
            
            <!-- Search Box -->
            <div class="w-full">
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input="filterData"
                        placeholder="Cari Nama, Unit Kerja, Jabatan..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Filter Jabatan -->
            <div class="flex flex-col sm:flex-row gap-3">
                <select 
                    x-model="filterJabatan"
                    @change="filterData"
                    class="px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jabatan</option>
                    <template x-for="jabatan in uniqueJabatan" :key="jabatan">
                        <option :value="jabatan" x-text="jabatan"></option>
                    </template>
                </select>
            </div>
        </div>
    </div>
                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Kerja</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <template x-for="k in filteredData" :key="k.id">
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                                <template x-if="k.foto">
                                                    <img :src="k.foto" :alt="k.nama" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!k.foto">
                                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 font-medium" x-text="k.nama"></td>
                                        <td class="px-6 py-4 text-sm text-gray-800" x-text="k.perusahaan ? k.perusahaan.nama_pt : '-'"></td>
                                        <td class="px-6 py-4 text-sm text-gray-800" x-text="k.jabatan"></td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button 
                                                    @click="selectedKaryawan = k; openDetail = true" 
                                                    class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition" 
                                                    title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>

                                                <button 
                                                    @click="selectedKaryawan = k; openEdit = true" 
                                                    class="text-yellow-500 hover:bg-yellow-50 p-2 rounded-lg transition" 
                                                    title="Edit Data">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                
                                                <form :id="'delete-form-' + k.id" :action="'/admin/karyawan/' + k.id" method="POST">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button 
                                                        type="button" 
                                                        @click="handleDelete(k.id)" 
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
                                
                                <template x-if="filteredData.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p class="text-lg font-medium">Tidak ada data ditemukan</p>
                                                <p class="text-sm text-gray-400 mt-1">Coba ubah kata kunci pencarian Anda</p>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden divide-y divide-gray-200">
                        <template x-for="k in filteredData" :key="k.id">
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <!-- Photo -->
                                    <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <template x-if="k.foto">
                                            <img :src="k.foto" :alt="k.nama" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!k.foto">
                                            <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </template>
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-gray-800 truncate" x-text="k.nama"></h3>
                                        <p class="text-sm text-gray-600 mt-1" x-text="k.perusahaan ? k.perusahaan.nama_pt : '-'"></p>
                                        <p class="text-sm text-gray-600" x-text="k.jabatan"></p>
                                        
                                        <!-- Action Buttons -->
                                        <div class="flex gap-2 mt-3">
                                            <button 
                                                @click="selectedKaryawan = k; openDetail = true" 
                                                class="flex-1 bg-blue-50 text-blue-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                                                Detail
                                            </button>
                                            <button 
                                                @click="selectedKaryawan = k; openEdit = true" 
                                                class="flex-1 bg-yellow-50 text-yellow-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-yellow-100 transition">
                                                Edit
                                            </button>
                                            <form :id="'delete-form-' + k.id" :action="'/admin/karyawan/' + k.id" method="POST" class="flex-1">
                                                @csrf 
                                                @method('DELETE')
                                                <button 
                                                    type="button" 
                                                    @click="handleDelete(k.id)" 
                                                    class="w-full bg-red-50 text-red-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="filteredData.length === 0">
                            <div class="p-10 text-center text-gray-500">
                                <svg class="w-16 h-16 text-gray-300 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-base font-medium">Tidak ada data ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah kata kunci pencarian</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Detail Karyawan --}}
    <div x-show="openDetail" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="openDetail = false"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-4 sm:p-6 z-50 relative max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">Detail Karyawan</h3>
                    <button @click="openDetail = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-center mb-4 sm:mb-6">
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center border-4 border-blue-100">
                            <template x-if="selectedKaryawan.foto">
                                <img :src="selectedKaryawan.foto" :alt="selectedKaryawan.nama" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!selectedKaryawan.foto">
                                <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </template>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">NIP</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 text-right" x-text="selectedKaryawan.nip"></span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Nama Lengkap</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 text-right" x-text="selectedKaryawan.nama"></span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Unit Kerja</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 text-right" x-text="selectedKaryawan.perusahaan ? selectedKaryawan.perusahaan.nama_pt : '-'"></span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Jabatan</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 text-right" x-text="selectedKaryawan.jabatan"></span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Alamat</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 text-right" x-text="selectedKaryawan.alamat || '-'"></span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Tanggal Lahir</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 text-right" x-text="selectedKaryawan.tanggal_lahir || '-'"></span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Nomor WhatsApp</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-800 text-right" x-text="selectedKaryawan.no_wa"></span>
                        </div>
                        
                        <div class="flex justify-between py-2">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Status</span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium" 
                                  :class="selectedKaryawan.status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                  x-text="selectedKaryawan.status"></span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6 flex justify-end">
                    <button @click="openDetail = false" class="px-4 sm:px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-sm sm:text-base">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Karyawan --}}
    <div x-show="openAdd" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="openAdd = false"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-4 sm:p-6 z-50 relative max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg sm:text-xl font-bold mb-4 text-gray-800">Tambah Karyawan Baru</h3>
                
                <form action="{{ route('admin.karyawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Karyawan</label>
                            <input type="file" name="foto" accept="image/*" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max: 5MB)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="nip" placeholder="Contoh: NIP001" value="{{ old('nip') }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" placeholder="Masukkan nama sesuai KTP" value="{{ old('nama_lengkap') }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja <span class="text-red-500">*</span></label>
                            <select name="perusahaan_id" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach($perusahaans as $p)
                                    <option value="{{ $p->id }}" {{ old('perusahaan_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" name="jabatan" placeholder="Contoh: Manager, Staff, dll" value="{{ old('jabatan') }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">{{ old('alamat') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>

                        <div x-data="{ showPassword: false }">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showPassword ? 'text' : 'password'" 
                                    name="password" 
                                    placeholder="Minimal 6 karakter" 
                                    class="w-full border border-gray-300 p-2 pr-10 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                    required>
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    tabindex="-1">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Password harus minimal 6 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Aktif</label>
                            <input type="text" name="nomor_wa" placeholder="Contoh: 0812xxxxxx" value="{{ old('nomor_wa') }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm" required>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" @click="openAdd = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition text-sm sm:text-base order-2 sm:order-1">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition text-sm sm:text-base order-1 sm:order-2">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Karyawan --}}
    <div x-show="openEdit" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="openEdit = false"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-4 sm:p-6 z-50 relative max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg sm:text-xl font-bold mb-4 text-gray-800">Edit Data Karyawan</h3>
                
                <form :action="'/admin/karyawan/' + selectedKaryawan.id" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Karyawan</label>
                            <input type="file" name="foto" accept="image/*" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                            <input type="text" name="nip" x-model="selectedKaryawan.nip" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" x-model="selectedKaryawan.nama" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja <span class="text-red-500">*</span></label>
                            <select name="perusahaan_id" x-model="selectedKaryawan.perusahaan_id" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                <template x-for="p in perusahaans" :key="p.id">
                                    <option :value="p.id" x-text="p.nama_pt"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" name="jabatan" x-model="selectedKaryawan.jabatan" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" x-model="selectedKaryawan.alamat" placeholder="Masukkan alamat lengkap" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" x-model="selectedKaryawan.tanggal_lahir" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm">
                        </div>

                        <div x-data="{ showPasswordEdit: false }">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru <span class="text-gray-500 text-xs">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showPasswordEdit ? 'text' : 'password'" 
                                    name="password" 
                                    placeholder="Minimal 6 karakter" 
                                    class="w-full border border-gray-300 p-2 pr-10 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm">
                                <button 
                                    type="button"
                                    @click="showPasswordEdit = !showPasswordEdit"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    tabindex="-1">
                                    <svg x-show="!showPasswordEdit" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPasswordEdit" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-blue-600 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Password saat ini sudah tersimpan. Isi hanya jika ingin mengubah.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                            <input type="text" name="nomor_wa" x-model="selectedKaryawan.no_wa" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-500 outline-none text-sm" required>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition text-sm sm:text-base order-2 sm:order-1">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-md transition text-sm sm:text-base order-1 sm:order-2">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function karyawanApp() {
            return {
                openAdd: false,
                openEdit: false,
                openDetail: false,
                selectedKaryawan: {},
                searchQuery: '',
                filterJabatan: '',
                allData: @json($data),
                filteredData: @json($data),
                perusahaans: @json($perusahaans),

                init() {
                    this.filterData();
                },

                get uniqueJabatan() {
                    return [...new Set(this.allData.map(k => k.jabatan))].sort();
                },

                filterData() {
                    let result = this.allData;

                    if (this.searchQuery.trim() !== '') {
                        const query = this.searchQuery.toLowerCase();
                        result = result.filter(k => {
                            return (
                                k.nama.toLowerCase().includes(query) ||
                                k.jabatan.toLowerCase().includes(query) ||
                                (k.nip && k.nip.toLowerCase().includes(query)) ||
                                (k.perusahaan && k.perusahaan.nama_pt.toLowerCase().includes(query))
                            );
                        });
                    }

                    if (this.filterJabatan !== '') {
                        result = result.filter(k => k.jabatan === this.filterJabatan);
                    }

                    this.filteredData = result;
                },

                handleDelete(id) {
                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: "Data karyawan akan dihapus secara permanen!",
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
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif
    </script>
</body>
</html>