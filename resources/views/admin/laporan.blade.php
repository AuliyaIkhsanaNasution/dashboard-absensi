@extends('layouts.app')

@section('title', 'Laporan')
@section('header', 'Laporan Kehadiran')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Filter Laporan</h3>
    
    <form method="GET" action="{{ route('admin.laporan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-700 font-medium mb-2">Periode</label>
            <select name="periode" id="periode" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="harian">Harian</option>
                <option value="bulanan" selected>Bulanan</option>
                <option value="tahunan">Tahunan</option>
                <option value="custom">Custom</option>
            </select>
        </div>

        <div id="filter_bulan">
            <label class="block text-gray-700 font-medium mb-2">Bulan</label>
            <input type="month" name="bulan" value="{{ date('Y-m') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div id="filter_tahun" class="hidden">
            <label class="block text-gray-700 font-medium mb-2">Tahun</label>
            <select name="tahun"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @for($i = date('Y'); $i >= date('Y')-5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-search mr-2"></i>Tampilkan
            </button>
        </div>
    </form>

    <div class="mt-4 flex gap-2">
        <button onclick="exportExcel()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-file-excel mr-2"></i>Export Excel
        </button>
        <button onclick="exportPDF()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
            <i class="fas fa-file-pdf mr-2"></i>Export PDF
        </button>
        <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-print mr-2"></i>Cetak
        </button>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-500 text-sm">Total Kehadiran</p>
        <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $totalHadir ?? 0 }}</h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-500 text-sm">Total Terlambat</p>
        <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $totalTerlambat ?? 0 }}</h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-500 text-sm">Total Izin</p>
        <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $totalIzin ?? 0 }}</h3>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <p class="text-gray-500 text-sm">Total Sakit</p>
        <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $totalSakit ?? 0 }}</h3>
    </div>
</div>

<!-- Tabel Laporan Detail -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Laporan Detail Kehadiran</h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laporan ?? [] as $index => $lap)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $lap->nip }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $lap->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">{{ $lap->hadir ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 font-semibold">{{ $lap->terlambat ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold">{{ $lap->izin ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">{{ $lap->sakit ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-semibold">{{ $lap->persentase ?? 0 }}%</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data laporan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Grafik Kehadiran (Opsional) -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Grafik Kehadiran</h3>
    <div class="h-64 flex items-center justify-center text-gray-400">
        <canvas id="chartKehadiran"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle filter berdasarkan periode
document.getElementById('periode').addEventListener('change', function() {
    const periode = this.value;
    const filterBulan = document.getElementById('filter_bulan');
    const filterTahun = document.getElementById('filter_tahun');
    
    if (periode === 'tahunan') {
        filterBulan.classList.add('hidden');
        filterTahun.classList.remove('hidden');
    } else if (periode === 'bulanan') {
        filterBulan.classList.remove('hidden');
        filterTahun.classList.add('hidden');
    } else if (periode === 'harian' || periode === 'custom') {
        filterBulan.classList.add('hidden');
        filterTahun.classList.add('hidden');
    }
});

function exportExcel() {
    // Implementasi export Excel
    alert('Fitur export Excel akan segera tersedia');
}

function exportPDF() {
    // Implementasi export PDF
    alert('Fitur export PDF akan segera tersedia');
}

// Chart.js untuk grafik (opsional)
// Uncomment jika ingin menggunakan Chart.js
/*
const ctx = document.getElementById('chartKehadiran').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Hadir', 'Terlambat', 'Izin', 'Sakit'],
        datasets: [{
            label: 'Jumlah',
            data: [{{ $totalHadir ?? 0 }}, {{ $totalTerlambat ?? 0 }}, {{ $totalIzin ?? 0 }}, {{ $totalSakit ?? 0 }}],
            backgroundColor: [
                'rgba(34, 197, 94, 0.5)',
                'rgba(234, 179, 8, 0.5)',
                'rgba(59, 130, 246, 0.5)',
                'rgba(239, 68, 68, 0.5)'
            ],
            borderColor: [
                'rgb(34, 197, 94)',
                'rgb(234, 179, 8)',
                'rgb(59, 130, 246)',
                'rgb(239, 68, 68)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
*/
</script>
@endsection