@extends('admin.layouts.app') {{-- Menggunakan layout utama aplikasi --}}

@section('content')

{{-- Judul halaman --}}
<h2 class="text-lg font-semibold text-primary mb-4">
Verifikasi Pembayaran
</h2>

{{-- ================= NOTIFIKASI ================= --}}

{{-- Menampilkan notifikasi sukses jika ada --}}
@if(session('success'))
<div class="bg-green-100 text-green-700 px-3 py-2 rounded mb-3 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Menampilkan notifikasi error jika ada --}}
@if(session('error'))
<div class="bg-red-100 text-red-700 px-3 py-2 rounded mb-3 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- ================= TABEL DATA PEMBAYARAN ================= --}}
<div class="bg-white rounded-lg shadow-sm border overflow-x-auto">

<table class="min-w-full text-sm">

{{-- Header tabel --}}
<thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
<tr>

{{-- Kolom nama pendaftar --}}
<th class="px-3 py-2 whitespace-nowrap">Nama</th>

{{-- Kolom bulan pembayaran --}}
<th class="px-3 py-2 whitespace-nowrap text-center">Bulan</th>

{{-- Kolom nominal pembayaran --}}
<th class="px-3 py-2 whitespace-nowrap text-center">Nominal</th>

{{-- Kolom link bukti pembayaran --}}
<th class="px-3 py-2 whitespace-nowrap text-center">Bukti</th>

{{-- Kolom status pembayaran --}}
<th class="px-3 py-2 whitespace-nowrap text-center">Status</th>

{{-- Kolom aksi (konfirmasi / tolak) --}}
<th class="px-3 py-2 whitespace-nowrap text-center">Aksi</th>

</tr>
</thead>

<tbody class="divide-y">

{{-- Looping data pembayaran --}}
@foreach($payments as $p)

<tr class="hover:bg-gray-50 transition">

{{-- Menampilkan nama pendaftar, jika tidak ada tampil '-' --}}
<td class="px-3 py-2 font-medium">
{{ $p->pendaftar->nama ?? '-' }}
</td>

{{-- Menampilkan bulan dari tanggal dibuat (created_at) --}}
<td class="px-3 py-2 text-center whitespace-nowrap">
{{ \Carbon\Carbon::parse($p->created_at)->format('M') }}
</td>

{{-- Menampilkan jumlah pembayaran dengan format rupiah --}}
<td class="px-3 py-2 text-center whitespace-nowrap font-medium tabular-nums">
Rp {{ number_format($p->jumlah) }}
</td>

{{-- Link untuk melihat bukti pembayaran --}}
<td class="px-3 py-2 text-center">
<a href="{{ asset('storage/' . $p->bukti) }}" target="_blank"
class="text-blue-600 hover:underline text-xs">
Lihat
</a>
</td>

{{-- ================= STATUS PEMBAYARAN ================= --}}
<td class="px-3 py-2 text-center">

@php
    // Menentukan warna berdasarkan status pembayaran
    $statusColor = match($p->status) {
        'menunggu' => 'bg-yellow-100 text-yellow-800',
        'diterima' => 'bg-green-100 text-green-700',
        'ditolak' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700'
    };
@endphp

{{-- Badge status --}}
<span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusColor }}">
    {{ ucfirst($p->status) }} {{-- Kapitalisasi huruf pertama --}}
</span>

</td>

{{-- ================= AKSI ================= --}}
<td class="px-3 py-2 text-center">

{{-- Jika status masih menunggu, tampilkan tombol aksi --}}
@if($p->status == 'menunggu')
<div class="flex justify-center gap-1">

{{-- Form konfirmasi pembayaran --}}
<form action="/admin/pembayaran/confirm" method="POST">
    @csrf {{-- Token keamanan Laravel --}}
    <input type="hidden" name="id" value="{{ $p->id }}"> {{-- ID pembayaran --}}
    
    {{-- Tombol konfirmasi --}}
    <button type="submit"
    onclick="return confirm('Yakin konfirmasi pembayaran ini?')"
    class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs">
        Konfirmasi
    </button>
</form>

{{-- Form penolakan pembayaran --}}
<form action="/admin/pembayaran/reject" method="POST">
    @csrf {{-- Token keamanan Laravel --}}
    <input type="hidden" name="id" value="{{ $p->id }}"> {{-- ID pembayaran --}}
    
    {{-- Tombol tolak --}}
    <button type="submit"
    onclick="return confirm('Yakin tolak pembayaran ini?')"
    class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded text-xs">
        Tolak
    </button>
</form>

</div>
@else
{{-- Jika sudah diproses, tampilkan status selesai --}}
<span class="text-gray-400 text-xs italic">Selesai</span>
@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection