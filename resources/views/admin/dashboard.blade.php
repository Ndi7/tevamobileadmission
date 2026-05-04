@extends('admin.layouts.app') {{-- Menggunakan layout utama aplikasi --}}

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-primary">
            {{ $title }} {{-- Judul halaman dari controller --}}
        </h2>

        <div class="flex gap-3">

            {{-- Dropdown export CSV --}}
            <div class="relative">
                <button onclick="toggleExport()" 
                        class="px-3 py-1 text-sm bg-primary text-white rounded">
                    Export CSV {{-- Tombol untuk menampilkan menu export --}}
                </button>

                {{-- Menu pilihan export --}}
                <div id="exportMenu" 
                     class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow text-sm">

                    {{-- Export semua data --}}
                    <a href="/admin/export-csv?type=all" 
                       class="block px-3 py-2 hover:bg-gray-100">
                        Semua
                    </a>

                    {{-- Export hanya siswa aktif --}}
                    <a href="/admin/export-csv?type=active" 
                       class="block px-3 py-2 hover:bg-gray-100">
                        Siswa Aktif
                    </a>

                    {{-- Export data pending --}}
                    <a href="/admin/export-csv?type=pending" 
                       class="block px-3 py-2 hover:bg-gray-100">
                        Pending
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- CARDS (RINGKASAN DATA) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Jumlah pendaftar baru --}}
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-primary">
            <p class="text-sm text-gray-500">Jumlah Pendaftar</p>
            <p class="text-3xl font-bold text-primary">
                {{ $newApplicants ?? 0 }} {{-- Default 0 jika kosong --}}
            </p>
            <p class="text-xs text-gray-400 mt-2">
                Pendaftar baru 7 hari terakhir
            </p>
        </div>

        {{-- Total siswa aktif --}}
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Siswa Aktif</p>
            <p class="text-3xl font-bold text-green-600">
                {{ $activeStudents ?? 0 }}
            </p>
            <p class="text-xs text-gray-400 mt-2">
                Total siswa aktif
            </p>
        </div>

        {{-- Pembayaran yang belum dikonfirmasi --}}
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Pembayaran Pending</p>
            <p class="text-3xl font-bold text-yellow-600">
                {{ $pendingPayments ?? 0 }}
            </p>
            <p class="text-xs text-gray-400 mt-2">
                Menunggu konfirmasi
            </p>
        </div>

    </div>

    <!-- GRAFIK + DATA TERBARU -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Grafik jumlah pendaftar --}}
        <div class="lg:col-span-2 bg-white p-5 rounded-lg shadow h-64">
            
            {{-- Canvas untuk Chart.js --}}
            <canvas id="chart"></canvas>

        </div>

        {{-- Daftar pendaftar terbaru --}}
        <div class="bg-white p-5 rounded-lg shadow">
            <h3 class="font-semibold mb-3">
                Pendaftar Terbaru
            </h3>

            <ul class="space-y-3 text-sm">

            {{-- Loop data terbaru --}}
            @forelse($recentApplicants as $item)

                <li class="flex justify-between">
                    <span>{{ $item->nama }}</span>
                    <span class="text-gray-500 text-xs">
                        {{ $item->kelas }} • {{ $item->status }}
                    </span>
                </li>

            @empty

                {{-- Jika tidak ada data --}}
                <li class="text-gray-400 text-sm">
                    Belum ada pendaftar
                </li>

            @endforelse

            </ul>

        </div>
    </div>
</div>

<!-- SCRIPT -->
{{-- Import library Chart.js untuk membuat grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// Fungsi untuk menampilkan / menyembunyikan menu export
function toggleExport() {
    document.getElementById('exportMenu').classList.toggle('hidden');
}

// KONFIGURASI CHART
const ctx = document.getElementById('chart');

// Membuat grafik line (garis)
new Chart(ctx, {
    type: 'line',
    data: {

        // Label sumbu X (misalnya tanggal)
        labels: @json($chartLabels),

        datasets: [{

            label: 'Pendaftar', // Judul data grafik

            // Data jumlah pendaftar
            data: @json($chartData),

            // Warna garis
            borderColor: '#3b82f6',

            // Warna area bawah grafik
            backgroundColor: 'rgba(59,130,246,0.2)',

            fill: true, // Area di bawah garis diisi warna

            tension: 0.3 // Kelengkungan garis
        }]
    },
    options: {
        responsive: true, // Menyesuaikan ukuran layar
        maintainAspectRatio: false, // Tinggi fleksibel
        scales: {
            y: {
                beginAtZero: true // Mulai dari angka 0
            }
        }
    }
});
</script>

@endsection