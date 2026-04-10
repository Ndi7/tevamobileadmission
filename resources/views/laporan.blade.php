@extends('layouts.app')

@section('content')
<div>
    <h2 class="text-xl font-semibold text-primary mb-4">Laporan</h2>

    <div class="bg-white rounded shadow p-4 mb-4">
        <div class="flex gap-2">
            <a href="/admin/export-csv?type=active" class="px-3 py-1 bg-blue-600 text-white rounded">Export Data Siswa → CSV</a>
            <a href="/admin/export-csv?type=all" class="px-3 py-1 bg-blue-600 text-white rounded">Export Pembayaran → CSV</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- 🔥 CHART -->
        <div class="bg-white rounded shadow p-4">
            <h3 class="font-semibold mb-3">Pendaftar Bulanan</h3>
            <canvas id="chartBulanan"></canvas>
        </div>

        <!-- 🔥 KEUANGAN -->
        <div class="bg-white rounded shadow p-4">
            <h3 class="font-semibold mb-3">Ringkasan Keuangan Bulanan</h3>
            <table class="w-full text-sm">
                <thead class="text-gray-600">
                    <tr>
                        <th class="p-2">Bulan</th>
                        <th class="p-2">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($keuangan as $item)

                    <tr class="border-t">
                        <td class="p-2">
                            {{ \Carbon\Carbon::create()->month($item->bulan)->format('M') }}
                        </td>
                        <td class="p-2">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="2" class="p-2 text-center text-gray-400">
                            Belum ada data
                        </td>
                    </tr>

                @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 🔥 CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('chartBulanan');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Jumlah Pendaftar',
            data: @json($bulanData),
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

@endsection