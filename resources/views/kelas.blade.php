@extends('layouts.app')

@section('content')

<h2 class="text-lg font-semibold text-primary mb-4">
Kelola Kelas
</h2>

<div class="bg-white rounded-lg shadow-sm border p-6">

<div class="flex justify-between items-center mb-6">

<div class="text-sm text-gray-500">
Daftar kelas yang tersedia
</div>

<button onclick="openModal()"
class="px-3 py-1.5 bg-primary hover:bg-blue-700 text-white rounded text-xs transition">
Tambah Kelas
</button>

</div>


@if (session('success'))
<div class="mb-4 px-3 py-2 bg-green-100 text-green-700 rounded text-sm">
{{ session('success') }}
</div>
@endif


<!-- GRID CARD -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

@foreach ($kelas as $k)

@php
$color = match($k->jenjang) {
'SD' => 'border-green-200 bg-green-50 text-green-700',
'SMP' => 'border-blue-200 bg-blue-50 text-blue-700',
'SMA', 'SMK' => 'border-purple-200 bg-purple-50 text-purple-700',
'Dewasa' => 'border-orange-200 bg-orange-50 text-orange-700',
default => 'border-gray-200 bg-gray-50 text-gray-700'
};
@endphp

<div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition {{ $color }}">

<div class="flex justify-between items-start mb-3">

<div class="font-semibold text-sm text-gray-800">
{{ $k->nama_kelas }}
</div>

<span class="px-2 py-0.5 bg-white/70 rounded text-xs font-medium">
{{ $k->jenjang }}
</span>

</div>

<div class="text-xs text-gray-500 mb-4">
Kelas bimbel aktif
</div>

<div class="flex gap-2">

<button
onclick="editModal('{{ $k->id }}','{{ $k->nama_kelas }}','{{ $k->jenjang }}')"
class="flex-1 px-2 py-1 bg-white hover:bg-gray-100 rounded text-xs border">
Edit
</button>

<form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="flex-1">
@csrf
@method('DELETE')

<button
onclick="return confirm('Yakin ingin menghapus kelas ini?')"
class="w-full px-2 py-1 bg-white hover:bg-red-50 text-red-600 rounded text-xs border">
Hapus
</button>

</form>

</div>

</div>

@endforeach

</div>

</div>


<!-- ================= MODAL TAMBAH ================= -->

<div id="modalTambah"
class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-[320px] p-6">

<h3 class="text-sm font-semibold mb-4">
Tambah Kelas
</h3>

<form action="{{ route('admin.kelas.store') }}" method="POST">
@csrf

<input
type="text"
name="nama_kelas"
placeholder="Nama kelas"
class="w-full border border-gray-300 px-3 py-2 rounded text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-primary"
required>

<select
name="jenjang"
class="w-full border border-gray-300 px-3 py-2 rounded text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-primary"
required>

<option value="">Pilih Jenjang</option>
<option value="SD">SD</option>
<option value="SMP">SMP</option>
<option value="SMA">SMA</option>
<option value="SMK">SMK</option>
<option value="Dewasa">Dewasa</option>

</select>

<div class="flex justify-end gap-2">

<button
type="button"
onclick="closeModal()"
class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs">
Batal
</button>

<button
type="submit"
class="px-3 py-1 bg-primary hover:bg-blue-700 text-white rounded text-xs">
Simpan
</button>

</div>

</form>

</div>

</div>


<!-- ================= MODAL EDIT ================= -->

<div id="modalEdit"
class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">

<div class="bg-white rounded-lg shadow-lg w-[320px] p-6">

<h3 class="text-sm font-semibold mb-4">
Edit Kelas
</h3>

<form id="editForm" method="POST">
@csrf
@method('PUT')

<input
id="editNama"
type="text"
name="nama_kelas"
class="w-full border border-gray-300 px-3 py-2 rounded text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-primary"
required>

<select
name="jenjang"
id="editJenjang"
class="w-full border border-gray-300 px-3 py-2 rounded text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-primary"
required>

<option value="SD">SD</option>
<option value="SMP">SMP</option>
<option value="SMA">SMA</option>
<option value="SMK">SMK</option>
<option value="Dewasa">Dewasa</option>

</select>

<div class="flex justify-end gap-2">

<button
type="button"
onclick="closeEdit()"
class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs">
Batal
</button>

<button
type="submit"
class="px-3 py-1 bg-primary hover:bg-blue-700 text-white rounded text-xs">
Update
</button>

</div>

</form>

</div>

</div>


<script>

function openModal(){
document.getElementById('modalTambah').classList.remove('hidden')
}

function closeModal(){
document.getElementById('modalTambah').classList.add('hidden')
}

function editModal(id, nama, jenjang){

document.getElementById('modalEdit').classList.remove('hidden')

document.getElementById('editNama').value = nama
document.getElementById('editJenjang').value = jenjang

document.getElementById('editForm').action = `/admin/kelas/${id}`

}

function closeEdit(){
document.getElementById('modalEdit').classList.add('hidden')
}

</script>

@endsection