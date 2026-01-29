<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Data Shift - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" 
      x-data="{ sidebarOpen: window.innerWidth >= 768, ...shiftApp() }"
      @resize.window="if(window.innerWidth >= 768) sidebarOpen = true">

    <div class="flex h-screen overflow-hidden">
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Shift Kerja</h1>
                            <p class="text-sm text-gray-600 mt-1 hidden sm:block">Pengaturan waktu kerja dan toleransi keterlambatan</p>
                        </div>
                        
                        <div class="flex gap-2">
                            <button @click="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span class="hidden sm:inline">Tambah Shift</span>
                            </button>
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8">
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="relative w-full sm:w-1/2">
                        <input type="text" x-model="searchQuery" @input="filterShift"
                               placeholder="Cari nama shift..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Shift</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Jam Masuk</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Jam Pulang</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Toleransi</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-for="shift in filteredShifts" :key="shift.id">
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800" x-text="shift.nama_shift"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="shift.jam_masuk.substring(0,5)"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600" x-text="shift.jam_pulang.substring(0,5)"></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs" x-text="shift.toleransi_menit + ' menit'"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button @click="openModal('edit', shift)" class="text-amber-500 hover:bg-amber-50 p-2 rounded-lg transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button type="button" @click="handleDelete(shift.id)" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
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

    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/60 transition-opacity" @click="showModal = false"></div>
            
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 z-50 relative transform transition-all">
                <h3 class="text-xl font-bold text-gray-800 mb-4" x-text="modalMode === 'add' ? 'Tambah Shift Baru' : 'Edit Shift'"></h3>
                
                <form
                :action="modalMode === 'add'
                ? '{{ route('admin.shift.store') }}'
                : '{{ route('admin.shift.update', ':id') }}'.replace(':id', selectedShift.id)"
                method="POST">
                @csrf
                <template x-if="modalMode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
                </template>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Shift</label>
                            <input type="text" name="nama_shift" x-model="selectedShift.nama_shift" required
                                   class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk</label>
                                <input type="time" name="jam_masuk" x-model="selectedShift.jam_masuk" required
                                       class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pulang</label>
                                <input type="time" name="jam_pulang" x-model="selectedShift.jam_pulang" required
                                       class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Toleransi (Menit)</label>
                            <input type="number" name="toleransi_menit" x-model="selectedShift.toleransi_menit" min="0" required
                                   class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-lg shadow-blue-200">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function shiftApp() {
        return {
            showModal: false,
            modalMode: 'add',
            selectedShift: {
                nama_shift: '',
                jam_masuk: '',
                jam_pulang: '',
                toleransi_menit: 0
            },
            searchQuery: '',
            allShifts: @json($shifts),
            filteredShifts: [],

            init() {
                this.filteredShifts = this.allShifts;
                
                @if(session('success'))
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Berhasil!', 
                        text: "{{ session('success') }}", 
                        timer: 3000, 
                        showConfirmButton: false 
                    });
                @endif
            },

            filterShift() {
                const query = this.searchQuery.toLowerCase();
                this.filteredShifts = this.allShifts.filter(s => 
                    s.nama_shift.toLowerCase().includes(query)
                );
            },

            openModal(mode, data = null) {
                this.modalMode = mode;
                if (mode === 'edit' && data) {
                    this.selectedShift = { 
                        ...data,
                        jam_masuk: data.jam_masuk.substring(0, 5),
                        jam_pulang: data.jam_pulang.substring(0, 5)
                    };
                } else {
                    this.selectedShift = { 
                        nama_shift: '', 
                        jam_masuk: '', 
                        jam_pulang: '', 
                        toleransi_menit: 0 
                    };
                }
                this.showModal = true;
            },

            handleDelete(id) {
    Swal.fire({
        title: 'Hapus Shift?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            
            // Gunakan path absolut agar tidak terjadi double slash atau salah alamat
            form.action = `/admin/shift/${id}`; 
            form.method = 'POST';

            // CSRF Token
            let csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Spoofing Method DELETE (Wajib untuk Laravel)
            let method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            
            form.submit();
        }
    });
}
        }
    }
</script>

    <style> [x-cloak] { display: none !important; } </style>
</body>
</html>