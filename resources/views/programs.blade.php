@extends('layouts.app')

@section('content')

<div>

<h2 class="text-lg font-semibold text-primary mb-5">
Kelola Program & Jenjang
</h2>

@if(session('success'))
<div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300 text-sm">
{{ session('success') }}
</div>
@endif


<div class="bg-white rounded-lg shadow-sm border p-5">

<div class="text-sm text-gray-500 mb-5">
Atur mata pelajaran yang tersedia untuk setiap kelas
</div>


@forelse($kelasList as $k)

@php
$paketKelas = $paket->where('kelas_id', $k->id);
$grouped = $paketKelas->groupBy('tipe');

$badge = match($k->jenjang){
'SD' => 'bg-green-100 text-green-700',
'SMP' => 'bg-blue-100 text-blue-700',
'SMA','SMK' => 'bg-purple-100 text-purple-700',
'Dewasa' => 'bg-orange-100 text-orange-700',
default => 'bg-gray-100 text-gray-700'
};
@endphp


<div x-data="{ open: false }"
class="border rounded-lg mb-4 overflow-hidden">

<button
type="button"
@click="open = !open"
class="flex w-full justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100">

<div class="text-left">

<div class="flex items-center gap-2">

<strong class="text-sm text-gray-800">
{{ $k->nama_kelas }}
</strong>

<span class="px-2 py-0.5 text-xs rounded {{ $badge }}">
{{ $k->jenjang }}
</span>

</div>

<div class="text-xs text-gray-500">
{{ $paketKelas->count() }} mapel terdaftar
</div>

</div>

<span class="text-gray-400 text-sm" x-show="!open">▼</span>
<span class="text-gray-400 text-sm" x-show="open">▲</span>

</button>


<div x-show="open" x-collapse class="p-4 bg-white border-t">


{{-- FORM TAMBAH MAPEL --}}
<form
action="{{ route('admin.programs.store') }}"
method="POST"
class="flex flex-wrap gap-3 items-center mb-5">

@csrf

<input type="hidden" name="kelas_id" value="{{ $k->id }}">

<select
name="mata_pelajaran_id"
class="border rounded px-3 py-2 text-sm"
required>

@foreach($mapelList as $m)
<option value="{{ $m->id }}">
{{ $m->nama_mapel }}
</option>
@endforeach

</select>

<select
name="tipe"
class="border rounded px-3 py-2 text-sm"
required>

<option value="wajib">Wajib</option>
<option value="reguler">Reguler</option>
<option value="ekskul">Ekskul</option>

</select>

<input
type="number"
name="harga"
class="border rounded px-3 py-2 text-sm w-32"
placeholder="Harga"
min="0"
required>

<button
class="px-3 py-2 bg-primary text-white rounded text-sm hover:bg-blue-700">
Tambah
</button>

</form>


@if($paketKelas->isEmpty())

<div class="text-sm text-gray-500">
Belum ada paket untuk kelas ini.
</div>

@else


<div class="grid grid-cols-1 md:grid-cols-3 gap-4">


@foreach(['wajib','reguler','ekskul'] as $t)

@php
$colors = [
'wajib' => 'bg-blue-50 border-blue-200',
'reguler' => 'bg-green-50 border-green-200',
'ekskul' => 'bg-purple-50 border-purple-200'
];
@endphp


<div class="border rounded-lg p-3 {{ $colors[$t] }}">

<h4 class="font-medium text-sm mb-3 border-b pb-2 capitalize">

{{ ucfirst($t) }}

@if(isset($grouped[$t]))

<div class="text-xs text-gray-500">
Total :
Rp {{ number_format($grouped[$t]->sum('harga'),0,',','.') }}
</div>

@endif

</h4>


@if(isset($grouped[$t]))

<ul class="space-y-2">

@foreach($grouped[$t] as $p)

<li class="flex justify-between items-center">

<div>

<div class="text-sm font-medium">
{{ $p->mapel->nama_mapel }}
</div>

<div class="text-xs text-gray-500">
Rp {{ number_format($p->harga,0,',','.') }}
</div>

</div>


<form
method="POST"
action="{{ route('admin.programs.destroy',$p->id) }}"
onsubmit="return confirm('Hapus mapel ini dari kelas?')">

@csrf
@method('DELETE')

<button
class="text-red-600 text-xs px-2 py-1 border border-red-300 rounded hover:bg-red-50">
Hapus
</button>

</form>

</li>

@endforeach

</ul>

@else

<div class="text-xs text-gray-500">
Tidak ada data
</div>

@endif

</div>

@endforeach


</div>

@endif


</div>

</div>


@empty

<div class="text-sm text-gray-500">
Belum ada data kelas.
</div>

@endforelse


</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection