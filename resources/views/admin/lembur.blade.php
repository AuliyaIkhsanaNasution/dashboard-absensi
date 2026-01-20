<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Lembur - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" 
      x-data="{ sidebarOpen: window.innerWidth >= 768, ...lemburApp() }"
      @resize.window="if(window.innerWidth >= 768) sidebarOpen = true">

    <div class="flex h-screen overflow-hidden">
        
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            
            <!-- Header with Hamburger -->
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Lembur Karyawan</h1>
                            <p class="text-sm text-gray-600 mt-1 hidden sm:block">Data pengajuan lembur berdasarkan urutan terbaru</p>
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
                
                {{-- Filter dan Pencarian --}}
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
                    <div class="flex flex-col gap-4">
                        
                        <!-- Search Box -->
                        <div class="w-full">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @input="filterLembur"
                                    placeholder="Cari nama karyawan..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Filter -->
                        <div class="flex gap-3">
                            <select 
                                x-model="filterKategori"
                                @change="filterLembur"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Semua Kategori</option>
                                <option value="Hari Kerja">Hari Kerja</option>
                                <option value="Hari Libur">Hari Libur</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tabel Lembur --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Karyawan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Lembur</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Waktu</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <template x-for="lembur in filteredLembur" :key="lembur.id">
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                    <template x-if="lembur.karyawan.foto">
                                                        <img :src="lembur.karyawan.foto" :alt="lembur.karyawan.nama" class="w-full h-full object-cover">
                                                    </template>
                                                    <template x-if="!lembur.karyawan.foto">
                                                        <span class="text-blue-600 font-bold text-sm" x-text="lembur.karyawan.nama.charAt(0)"></span>
                                                    </template>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800" x-text="lembur.karyawan.nama"></p>
                                                    <p class="text-xs text-gray-500" x-text="lembur.karyawan.jabatan"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800" x-text="formatTanggal(lembur.tgl_lembur)"></td>
                                        <td class="px-6 py-4 text-sm text-gray-800">
                                            <span x-text="lembur.jam_mulai.substring(0,5)"></span> - 
                                            <span x-text="lembur.jam_selesai.substring(0,5)"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span :class="lembur.kategori === 'Hari Kerja' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800'" 
                                                class="px-3 py-1 rounded-full text-xs font-medium" 
                                                x-text="lembur.kategori"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <button 
                                                    @click="selectedLembur = lembur; openView = true" 
                                                    class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition" 
                                                    title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                <form :id="'delete-form-' + lembur.id" :action="'/admin/lembur/' + lembur.id" method="POST">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button 
                                                        type="button" 
                                                        @click="handleDelete(lembur.id)" 
                                                        class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition"
                                                        title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="filteredLembur.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-lg font-medium">Tidak ada data lembur</p>
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
                        <template x-for="lembur in filteredLembur" :key="lembur.id">
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-3 mb-3">
                                    <!-- Photo -->
                                    <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <template x-if="lembur.karyawan.foto">
                                            <img :src="lembur.karyawan.foto" :alt="lembur.karyawan.nama" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!lembur.karyawan.foto">
                                            <span class="text-blue-600 font-bold text-sm" x-text="lembur.karyawan.nama.charAt(0)"></span>
                                        </template>
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="lembur.karyawan.nama"></p>
                                        <p class="text-xs text-gray-500" x-text="lembur.karyawan.jabatan"></p>
                                        <div class="mt-2">
                                            <span 
                                                :class="lembur.kategori === 'Hari Kerja' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800'" 
                                                class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                                                x-text="lembur.kategori"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Detail Info -->
                                <div class="mb-3 space-y-1 text-xs text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span x-text="formatTanggal(lembur.tgl_lembur)"></span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span x-text="lembur.jam_mulai.substring(0,5) + ' - ' + lembur.jam_selesai.substring(0,5)"></span>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button 
                                        @click="selectedLembur = lembur; openView = true"
                                        class="flex-1 bg-blue-50 text-blue-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                                        Lihat Detail
                                    </button>
                                    <form :id="'delete-form-' + lembur.id" :action="'/admin/lembur/' + lembur.id" method="POST" class="flex-1">
                                        @csrf 
                                        @method('DELETE')
                                        <button 
                                            type="button" 
                                            @click="handleDelete(lembur.id)"
                                            class="w-full bg-red-50 text-red-600 py-2 px-3 rounded-lg text-sm font-medium hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="filteredLembur.length === 0">
                            <div class="p-10 text-center text-gray-500">
                                <svg class="w-16 h-16 text-gray-300 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-base font-medium">Tidak ada data lembur</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau kata kunci</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal View Detail --}}
    <div x-show="openView" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/60" @click="openView = false"></div>
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-2xl p-4 sm:p-6 z-50 relative max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Detail Lembur</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 truncate" x-text="'Karyawan: ' + selectedLembur.karyawan?.nama"></p>
                    </div>
                    <button @click="openView = false" class="text-gray-400 hover:text-gray-600 text-2xl ml-2 flex-shrink-0">&times;</button>
                </div>

                <div class="mb-4 p-3 sm:p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 font-medium mb-1 text-xs sm:text-sm">Tanggal Lembur:</p>
                            <p class="text-gray-800 font-semibold text-sm" x-text="formatTanggal(selectedLembur.tgl_lembur)"></p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium mb-1 text-xs sm:text-sm">Kategori:</p>
                            <p class="text-gray-800 font-semibold text-sm" x-text="selectedLembur.kategori"></p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium mb-1 text-xs sm:text-sm">Jam Mulai:</p>
                            <p class="text-gray-800 font-semibold text-sm" x-text="selectedLembur.jam_mulai?.substring(0,5)"></p>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium mb-1 text-xs sm:text-sm">Jam Selesai:</p>
                            <p class="text-gray-800 font-semibold text-sm" x-text="selectedLembur.jam_selesai?.substring(0,5)"></p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-blue-200">
                        <p class="text-gray-500 font-medium mb-2 text-xs sm:text-sm">Keterangan:</p>
                        <p class="text-gray-700 text-xs sm:text-sm" x-text="selectedLembur.keterangan || 'Tidak ada keterangan.'"></p>
                    </div>
                    <div class="mt-3 pt-3 border-t border-blue-200">
                        <p class="text-gray-500 font-medium mb-1 text-xs sm:text-sm">Status:</p>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold capitalize"
                            :class="{
                                'bg-yellow-100 text-yellow-800': selectedLembur.status === 'pending',
                                'bg-green-100 text-green-800': selectedLembur.status === 'approved',
                                'bg-red-100 text-red-800': selectedLembur.status === 'rejected'
                            }"
                            x-text="selectedLembur.status">
                        </span>
                    </div>
                </div>
                
                <div>
                    <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-2">Dokumen Lampiran:</p>
                    <div class="bg-gray-100 rounded-lg sm:rounded-xl overflow-hidden min-h-[200px] sm:min-h-[300px] flex items-center justify-center border-2 border-dashed border-gray-300">
                        <template x-if="selectedLembur && selectedLembur.dokumen">
                            <img :src="'/storage/' + selectedLembur.dokumen" class="max-w-full max-h-[60vh] sm:max-h-[70vh] object-contain">
                        </template>
                        <template x-if="!selectedLembur || !selectedLembur.dokumen">
                            <div class="text-center p-6 sm:p-8">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 text-xs sm:text-sm">Tidak ada dokumen lampiran.</p>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6 grid grid-cols-3 gap-2 sm:gap-3">
                    {{-- Pending --}}
                    <form
                        :action="'{{ url('/admin/lembur') }}/' + selectedLembur.id + '/status'"
                        method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="pending">
                        <button
                            type="submit"
                            class="w-full py-2 rounded-lg font-bold transition text-xs sm:text-sm"
                            :class="selectedLembur.status === 'pending'
                                ? 'bg-yellow-400 text-white cursor-not-allowed'
                                : 'bg-yellow-500 hover:bg-yellow-600 text-white'">
                            Pending
                        </button>
                    </form>

                    {{-- Approve --}}
                    <form
                        :action="'{{ url('/admin/lembur') }}/' + selectedLembur.id + '/status'"
                        method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button
                            type="submit"
                            class="w-full py-2 rounded-lg font-bold transition text-xs sm:text-sm"
                            :class="selectedLembur.status === 'approved'
                                ? 'bg-green-500 text-white cursor-not-allowed'
                                : 'bg-green-600 hover:bg-green-700 text-white'">
                            Approve
                        </button>
                    </form>

                    {{-- Reject --}}
                    <form
                        :action="'{{ url('/admin/lembur') }}/' + selectedLembur.id + '/status'"
                        method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button
                            type="submit"
                            class="w-full py-2 rounded-lg font-bold transition text-xs sm:text-sm"
                            :class="selectedLembur.status === 'rejected'
                                ? 'bg-red-500 text-white cursor-not-allowed'
                                : 'bg-red-600 hover:bg-red-700 text-white'">
                            Reject
                        </button>
                    </form>
                </div>

                <div class="mt-4 sm:mt-6">
                    <button @click="openView = false" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 sm:py-3 rounded-lg transition text-sm sm:text-base">Tutup</button>
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
        function lemburApp() {
            return {
                openView: false,
                selectedLembur: {},
                searchQuery: '',
                filterKategori: '',
                allLembur: @json($allLembur),
                filteredLembur: [],

                init() {
                    this.filteredLembur = this.allLembur;
                },

                filterLembur() {
                    let result = this.allLembur;

                    if (this.searchQuery.trim() !== '') {
                        const query = this.searchQuery.toLowerCase();
                        result = result.filter(l =>
                            l.karyawan.nama.toLowerCase().includes(query) ||
                            l.karyawan.jabatan.toLowerCase().includes(query)
                        );
                    }

                    if (this.filterKategori !== '') {
                        result = result.filter(l => l.kategori === this.filterKategori);
                    }

                    this.filteredLembur = result;
                },

                formatTanggal(tgl) {
                    if(!tgl) return '-';
                    const date = new Date(tgl);
                    const options = { day: '2-digit', month: 'short', year: 'numeric' };
                    return date.toLocaleDateString('id-ID', options);
                },

                handleDelete(id) {
                    Swal.fire({
                        title: 'Hapus data lembur?',
                        text: "Tindakan ini akan menghapus data secara permanen!",
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