@extends('layouts.app')

@section('content')

<h2 class="text-lg font-semibold text-primary mb-4">
Daftar Pendaftar
</h2>

<div class="bg-white rounded-lg shadow-sm border">
<div class="overflow-x-auto">

<table class="min-w-full text-sm">

<thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
<tr>

<th class="px-3 py-2">Foto</th>
<th class="px-3 py-2">Nama</th>
<th class="px-3 py-2">Sekolah</th>
<th class="px-3 py-2">Kelas</th>
<th class="px-3 py-2">Program</th>
<th class="px-3 py-2 whitespace-nowrap">Biaya</th>
<th class="px-3 py-2">Status</th>
<th class="px-3 py-2">Aksi</th>

</tr>
</thead>

<tbody class="divide-y">

@foreach($pendaftar as $p)

<!-- ROW UTAMA -->
<tr class="hover:bg-gray-50">

<td class="px-3 py-2">
@if($p->foto)
<img src="{{ asset('storage/'.$p->foto) }}"
class="w-12 h-12 object-cover rounded-md border">
@else
-
@endif
</td>

<td class="px-3 py-2 font-medium">
{{ $p->nama }}
</td>

<td class="px-3 py-2">
{{ $p->sekolah }}
</td>

<td class="px-3 py-2">
{{ $p->kelas }}
</td>

<!-- PROGRAM -->
<td class="px-3 py-2">

@php
$wajib = json_decode($p->mapel_wajib) ?? [];
$reguler = json_decode($p->mapel_reguler) ?? [];
$ekskul = json_decode($p->mapel_ekskul) ?? [];
@endphp

<div class="flex flex-wrap gap-1">

@foreach($wajib as $m)
<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">
{{ $m->nama }}
</span>
@endforeach

@foreach($reguler as $m)
<span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">
{{ $m->nama }}
</span>
@endforeach

@foreach($ekskul as $m)
<span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">
{{ $m->nama }}
</span>
@endforeach

</div>

</td>

<td class="px-3 py-2 font-medium whitespace-nowrap">
Rp {{ number_format($p->total_harga) }}
</td>

<td class="px-3 py-2">

<span class="px-2 py-0.5 rounded text-xs font-medium
@if($p->status == 'DITERIMA')
bg-green-100 text-green-800
@elseif($p->status == 'DITOLAK')
bg-red-100 text-red-800
@else
bg-yellow-100 text-yellow-800
@endif
">

{{ $p->status ?? 'MENUNGGU' }}

</span>

</td>

<td class="px-3 py-2">

<div class="flex gap-1">

<button
onclick="toggleDetail('{{ $p->id }}')"
class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs">
Detail
</button>

<form action="/admin/pendaftaran/accept" method="POST">
@csrf
<input type="hidden" name="id" value="{{ $p->id }}">

<button class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs">
Terima
</button>

</form>

<form action="/admin/pendaftaran/reject" method="POST">
@csrf
<input type="hidden" name="id" value="{{ $p->id }}">

<button class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded text-xs">
Tolak
</button>
</form>

</div>

</td>

</tr>


<!-- DETAIL ROW -->
<tr id="detail-{{ $p->id }}" class="hidden bg-gray-50">

<td colspan="8" class="px-6 py-4">

<div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">

<div>
<span class="text-gray-500 text-xs">Nama Ayah</span>
<div class="font-medium">{{ $p->nama_ayah }}</div>
</div>

<div>
<span class="text-gray-500 text-xs">Nama Ibu</span>
<div class="font-medium">{{ $p->nama_ibu }}</div>
</div>

<div>
<span class="text-gray-500 text-xs">HP Orang Tua</span>
<div>{{ $p->hp_orangtua }}</div>
</div>

<div>
<span class="text-gray-500 text-xs">HP Siswa</span>
<div>{{ $p->hp_siswa }}</div>
</div>

<div>
<span class="text-gray-500 text-xs">Agama</span>
<div>{{ $p->agama }}</div>
</div>

<div>
<span class="text-gray-500 text-xs">Tanggal Lahir</span>
<div>{{ $p->tanggal_lahir }}</div>
</div>

<div class="col-span-2 md:col-span-3">
<span class="text-gray-500 text-xs">Alamat</span>
<div>{{ $p->alamat }}</div>
</div>

</div>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>



<script>

function toggleDetail(id){

const row = document.getElementById('detail-'+id)

if(row.classList.contains('hidden')){
row.classList.remove('hidden')
}else{
row.classList.add('hidden')
}

}

</script>

@endsection