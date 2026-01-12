<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Lembur - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" x-data="lemburApp()">

    <div class="flex h-screen overflow-hidden">
        
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Lembur Karyawan</h1>
                    <p class="text-sm text-gray-600 mt-1">Data pengajuan lembur berdasarkan urutan terbaru</p>
                </div>
            </header>

            <div class="p-4">
                
                {{-- Filter dan Pencarian --}}
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @input="filterLembur"
                                    placeholder="Cari nama karyawan..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <select 
                                x-model="filterKategori"
                                @change="filterLembur"
                                class="px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Kategori</option>
                                <option value="Hari Kerja">Hari Kerja</option>
                                <option value="Hari Libur">Hari Libur</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tabel Lembur --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
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
                                                <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                                    <template x-if="lembur.karyawan.foto">
                                                        <img :src="'/storage/' + lembur.karyawan.foto" :alt="lembur.karyawan.nama" class="w-full h-full object-cover">
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
                                                {{-- Tombol View --}}
                                                <button 
                                                    @click="selectedLembur = lembur; openView = true" 
                                                    class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition" 
                                                    title="Lihat Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                {{-- Form Delete --}}
                                                <form :id="'delete-form-' + lembur.id" :action="'/admin/lembur/' + lembur.id" method="POST">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button 
                                                        type="button" 
                                                        @click="handleDelete(lembur.id)" 
                                                        class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal View Detail (Keterangan & Dokumen) --}}
    <div x-show="openView" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/60" @click="openView = false"></div>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 z-50 relative">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Detail Lampiran Lembur</h3>
                        <p class="text-sm text-gray-500 mt-1" x-text="'Karyawan: ' + selectedLembur.karyawan?.nama"></p>
                    </div>
                    <button @click="openView = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Keterangan Pekerjaan:</p>
                        <p class="text-gray-700 leading-relaxed" x-text="selectedLembur.keterangan || 'Tidak ada keterangan.'"></p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Dokumen Pendukung:</p>
                        <div class="bg-gray-100 rounded-xl overflow-hidden min-h-[300px] flex items-center justify-center border-2 border-dashed border-gray-300">
                            <template x-if="selectedLembur.dokumen">
                                <img :src="'/storage/' + selectedLembur.dokumen" class="max-w-full max-h-[60vh] object-contain">
                            </template>
                            <template x-if="!selectedLembur.dokumen">
                                <div class="text-center p-8">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">Tidak ada dokumen lampiran.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button @click="openView = false" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2.5 rounded-lg transition">Tutup Detail</button>
                </div>
            </div>
        </div>
    </div>

    <style> 
        [x-cloak] { display: none !important; }
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
                    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                },

                handleDelete(id) {
                    Swal.fire({
                        title: 'Hapus data lembur?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    });
                }
            }
        }
    </script>
</body>
</html>