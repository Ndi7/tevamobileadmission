@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-primary">{{ $title }}</h2>
        <div class="flex gap-3">
            <div class="relative">
                <button onclick="toggleExport()" class="px-3 py-1 text-sm bg-primary text-white rounded">
                    Export CSV
                </button>

                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow text-sm">
                    <a href="/admin/export-csv?type=all" class="block px-3 py-2 hover:bg-gray-100">Semua</a>
                    <a href="/admin/export-csv?type=active" class="block px-3 py-2 hover:bg-gray-100">Siswa Aktif</a>
                    <a href="/admin/export-csv?type=pending" class="block px-3 py-2 hover:bg-gray-100">Pending</a>
                </div>
            </div>

            <!-- <a href="/pendaftaran" class="px-3 py-1 border rounded text-sm">Lihat Pendaftar</a> -->
        </div>
    </div>

    <!-- cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-primary">
            <p class="text-sm text-gray-500">Jumlah Pendaftar</p>
            <p class="text-3xl font-bold text-primary">{{ $newApplicants ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Pendaftar baru 7 hari terakhir</p>
        </div>

        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Siswa Aktif</p>
            <p class="text-3xl font-bold text-green-600">{{ $activeStudents ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Total siswa aktif</p>
        </div>

        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Pembayaran Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingPayments ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-2">Menunggu konfirmasi</p>
        </div>
    </div>

    <!-- grafik + recent registrants -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-5 rounded-lg shadow h-64">
            
            <!-- 🔥 CHART -->
            <canvas id="chart"></canvas>

        </div>

        <div class="bg-white p-5 rounded-lg shadow">
            <h3 class="font-semibold mb-3">Pendaftar Terbaru</h3>

            <ul class="space-y-3 text-sm">

            @forelse($recentApplicants as $item)

                <li class="flex justify-between">
                    <span>{{ $item->nama }}</span>
                    <span class="text-gray-500 text-xs">
                        {{ $item->kelas }} • {{ $item->status }}
                    </span>
                </li>

            @empty

                <li class="text-gray-400 text-sm">
                    Belum ada pendaftar
                </li>

            @endforelse

            </ul>

        </div>
    </div>
</div>

<!-- 🔥 SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function toggleExport() {
    document.getElementById('exportMenu').classList.toggle('hidden');
}

// 🔥 CHART CONFIG
const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Pendaftar',
            data: @json($chartData),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

@endsection