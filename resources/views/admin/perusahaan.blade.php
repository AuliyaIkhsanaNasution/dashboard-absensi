<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Perusahaan - PT. Souci Indoprima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .leaflet-container { z-index: 1; }
    </style>
</head>
<body class="bg-gray-100" x-data="{ 
    openAdd: false, 
    openEdit: false, 
    openView: false,
    openKaryawan: false,
    listKaryawan: [],
    selectedPerusahaan: {} 
}">

    <div class="flex h-screen overflow-hidden">
        <x-sidebar />

        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-800">Data Perusahaan</h1>
                    <p class="text-sm text-gray-600 mt-1">Kelola informasi identitas PT Outsourcing</p>
                </div>
            </header>

            @if($errors->any())
            <div class="mx-8 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="p-4">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1 max-w-md">
                            <h2 class="text-lg font-semibold text-gray-700">Daftar Cabang / Unit</h2>
                        </div>
                        <div class="flex gap-3">
                            <button @click="openAdd = true; initMap('mapAdd', 'lat_add', 'lng_add')" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Perusahaan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Logo</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Unit Kerja</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Total Karyawan</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($perusahaans as $p)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        @if($p->logo)
                                            <img src="{{ asset('storage/' . $p->logo) }}" class="h-10 w-10 object-cover rounded border">
                                        @else
                                            <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center text-[10px] text-gray-400">No Logo</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $p->nama_pt }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                            <span 
                                                @click="listKaryawan = {{ json_encode($p->karyawans) }}; openKaryawan = true"
                                                class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold cursor-pointer hover:bg-blue-200 transition"
                                                title="Klik untuk lihat daftar nama"
                                            >
                                                {{ $p->karyawans_count ?? $p->karyawans->count() }} Orang
                                            </span>
                                        </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="selectedPerusahaan = {{ json_encode($p) }}; openView = true; initViewMap($p.latitude, $p.longitude)" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg" title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>

                                            <button @click="selectedPerusahaan = {{ json_encode($p) }}; openEdit = true; initMap('mapEdit', 'lat_edit', 'lng_edit', $p.latitude, $p.longitude)" class="text-yellow-500 hover:bg-yellow-50 p-2 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            
                                            <form id="delete-perusahaan-{{ $p->id }}" action="{{ route('admin.perusahaan.destroy', $p->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="handleDeletePerusahaan('{{ $p->id }}')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div x-show="openView" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="openView = false"></div>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-6 z-50">
                <div class="flex justify-between items-center mb-6 border-b pb-3">
                    <h3 class="text-xl font-bold text-gray-800">Detail Perusahaan</h3>
                    <button @click="openView = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Unit</label>
                            <p class="text-lg font-bold text-blue-600" x-text="selectedPerusahaan.nama_pt"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Email</label>
                                <p class="text-sm text-gray-700" x-text="selectedPerusahaan.email"></p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Telepon</label>
                                <p class="text-sm text-gray-700" x-text="selectedPerusahaan.telepon"></p>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Alamat</label>
                            <p class="text-sm text-gray-700" x-text="selectedPerusahaan.alamat"></p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <label class="text-xs font-semibold text-gray-500 uppercase">Koordinat Lokasi</label>
                            <p class="text-sm font-mono text-gray-600" x-text="selectedPerusahaan.latitude + ', ' + selectedPerusahaan.longitude"></p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase mb-2 block">Peta Lokasi</label>
                        <div id="mapView" class="h-64 w-full rounded-lg border shadow-inner"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="openKaryawan" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50" @click="openKaryawan = false"></div>
        
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 z-[61] relative">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800">Daftar Karyawan</h3>
                <button @click="openKaryawan = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            
            <div class="max-h-96 overflow-y-auto">
                <template x-if="listKaryawan.length > 0">
                    <ul class="divide-y divide-gray-100">
                        <template x-for="(karyawan, index) in listKaryawan" :key="index">
                            <li class="py-3 flex items-center gap-3">
                                <div class="h-8 w-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold" x-text="index + 1"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800" x-text="karyawan.nama"></p>
                                    <p class="text-xs text-gray-500" x-text="karyawan.jabatan || 'Karyawan'"></p>
                                </div>
                            </li>
                        </template>
                    </ul>
                </template>
                
                <template x-if="listKaryawan.length === 0">
                    <div class="text-center py-8 text-gray-500 italic">
                        Tidak ada karyawan terdaftar.
                    </div>
                </template>
            </div>

            <div class="mt-6 flex justify-end">
                <button @click="openKaryawan = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Tutup</button>
            </div>
        </div>
    </div>
</div>

    <div x-show="openAdd" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="openAdd = false"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 z-50">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Tambah Perusahaan</h3>
                <form action="{{ route('admin.perusahaan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                                <input type="text" name="nama_pt" class="w-full border border-gray-300 p-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" class="w-full border border-gray-300 p-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                    <input type="text" name="telepon" class="w-full border border-gray-300 p-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea name="alamat" rows="2" class="w-full border border-gray-300 p-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                    <input type="text" name="latitude" id="lat_add" class="w-full border bg-gray-50 p-2 rounded-lg outline-none" readonly required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                    <input type="text" name="longitude" id="lng_add" class="w-full border bg-gray-50 p-2 rounded-lg outline-none" readonly required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                                <input type="file" name="logo" class="w-full text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari & Tandai Lokasi</label>
                            <div id="mapAdd" class="h-full min-h-[300px] w-full rounded-lg border"></div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openAdd = false" class="px-4 py-2 text-gray-600">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="openEdit" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="openEdit = false"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 z-50">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Edit Perusahaan</h3>
                <form :action="'/admin/perusahaan/' + selectedPerusahaan.id" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                                <input type="text" name="nama_pt" x-model="selectedPerusahaan.nama_pt" class="w-full border p-2 rounded-lg outline-none focus:ring-2 focus:ring-yellow-500" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" x-model="selectedPerusahaan.email" class="w-full border p-2 rounded-lg" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                    <input type="text" name="telepon" x-model="selectedPerusahaan.telepon" class="w-full border p-2 rounded-lg" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea name="alamat" x-model="selectedPerusahaan.alamat" rows="2" class="w-full border p-2 rounded-lg" required></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                    <input type="text" name="latitude" id="lat_edit" x-model="selectedPerusahaan.latitude" class="w-full border bg-gray-50 p-2 rounded-lg outline-none" readonly required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                    <input type="text" name="longitude" id="lng_edit" x-model="selectedPerusahaan.longitude" class="w-full border bg-gray-50 p-2 rounded-lg outline-none" readonly required>
                                </div>
                            </div>
                        </div>
                        <div id="mapEdit" class="h-full min-h-[300px] w-full rounded-lg border"></div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 text-gray-600">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let maps = {};
        let markers = {};

        function initMap(mapId, latId, lngId, existLat = -6.200000, existLng = 106.816666) {
            setTimeout(() => {
                // Hapus map lama jika ada agar tidak bentrok
                if (maps[mapId]) { maps[mapId].remove(); }

                maps[mapId] = L.map(mapId).setView([existLat, existLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(maps[mapId]);

                markers[mapId] = L.marker([existLat, existLng], {draggable: true}).addTo(maps[mapId]);

                // Fitur Pencarian
                const geocoder = L.Control.Geocoder.nominatim();
                L.Control.geocoder({ defaultMarkGeocode: false })
                    .on('markgeocode', function(e) {
                        const bbox = e.geocode.bbox;
                        const center = e.geocode.center;
                        maps[mapId].fitBounds(bbox);
                        markers[mapId].setLatLng(center);
                        document.getElementById(latId).value = center.lat;
                        document.getElementById(lngId).value = center.lng;
                    })
                    .addTo(maps[mapId]);

                // Update koordinat saat marker digeser
                markers[mapId].on('dragend', function(e) {
                    const coords = e.target.getLatLng();
                    document.getElementById(latId).value = coords.lat;
                    document.getElementById(lngId).value = coords.lng;
                });
                
                // Refresh ukuran map agar tampil sempurna
                maps[mapId].invalidateSize();
            }, 300);
        }

        function initViewMap(lat, lng) {
            setTimeout(() => {
                if (maps['mapView']) { maps['mapView'].remove(); }
                maps['mapView'] = L.map('mapView').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(maps['mapView']);
                L.marker([lat, lng]).addTo(maps['mapView']);
                maps['mapView'].invalidateSize();
            }, 300);
        }

        function handleDeletePerusahaan(id) {
            Swal.fire({
                title: 'Hapus Perusahaan?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('delete-perusahaan-' + id).submit(); }
            })
        }

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
    </script>
</body>
</html>