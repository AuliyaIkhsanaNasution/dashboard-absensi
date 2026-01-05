<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" x-data="{ openAdd: false, openEdit: false, selectedKaryawan: {} }">

    <div class="flex h-screen overflow-hidden">
        
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-800">Data Karyawan</h1>
                    <p class="text-sm text-gray-600 mt-1">Kelola data karyawan PT. Souci Indoprima</p>
                </div>
            </header>

            <div class="p-4">
                
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <input type="text" placeholder="Cari nama, NIP..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <select class="px-4 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Jabatan</option>
                            </select>
                            
                            <button @click="openAdd = true" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Karyawan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIP</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penempatan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor WA</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($data as $k)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $k->nip }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $k->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $k->jabatan }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $k->penempatan }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ $k->no_wa }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $k->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $k->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="selectedKaryawan = {{ json_encode($k) }}; openEdit = true" class="text-yellow-500 hover:bg-yellow-50 p-2 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            
                                            <form action="{{ route('admin.karyawan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada data karyawan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div x-show="openAdd" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50" @click="openAdd = false"></div>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 z-50">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Tambah Karyawan Baru</h3>
            
            <form action="{{ route('admin.karyawan.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Pegawai (NIP)</label>
                        <input type="text" name="nip" placeholder="Contoh: NIP001" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" placeholder="Masukkan nama sesuai KTP" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <input type="text" name="jabatan" placeholder="Contoh: Manager, Staff, dll" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penempatan Kerja</label>
                        <input type="text" name="penempatan" placeholder="Contoh: Kantor Pusat, Gudang A" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Aktif</label>
                        <input type="text" name="nomor_wa" placeholder="Contoh: 0812xxxxxx" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="openAdd = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <div x-show="openEdit" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 z-50">
                <h3 class="text-lg font-bold mb-4">Edit Data Karyawan</h3>
                <form :action="'/admin/karyawan/' + selectedKaryawan.id" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <input type="text" name="nip" x-model="selectedKaryawan.nip" class="w-full border p-2 rounded">
                        <input type="text" name="nama_lengkap" x-model="selectedKaryawan.nama_lengkap" class="w-full border p-2 rounded">
                        <input type="text" name="jabatan" x-model="selectedKaryawan.jabatan" class="w-full border p-2 rounded">
                        <input type="text" name="penempatan" x-model="selectedKaryawan.penempatan" class="w-full border p-2 rounded">
                        <input type="text" name="nomor_wa" x-model="selectedKaryawan.nomor_wa" class="w-full border p-2 rounded">
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 text-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style> [x-cloak] { display: none !important; } </style>
</body>
</html>