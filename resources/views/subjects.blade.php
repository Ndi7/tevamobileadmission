@extends('layouts.app')
@section('content')

<div>

<div class="flex items-center justify-between mb-5">
    <h2 class="text-lg font-semibold text-primary">
        Kelola Mata Pelajaran
    </h2>

    <button onclick="openAddModal()"
        class="px-3 py-1.5 bg-primary text-white rounded text-xs hover:bg-blue-700 transition">
        + Tambah Mapel
    </button>
</div>


@if(session('success'))
<div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300 text-sm">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300 text-sm">
    Terjadi kesalahan:
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="bg-white rounded-lg shadow-sm border p-5">

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

@foreach($subjects as $s)

<div class="border rounded-lg p-4 hover:shadow-md transition bg-white">

<div class="flex justify-between items-start mb-3">

<div class="font-medium text-sm text-gray-800">
{{ $s->nama_mapel }}
</div>

<span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded">
Mapel
</span>

</div>

<div class="text-xs text-gray-500 mb-4">
Mata pelajaran bimbel
</div>

<div class="flex gap-2">

<button
onclick='openEditModal(@json($s))'
class="flex-1 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs hover:bg-yellow-200">
Edit
</button>

<form
action="{{ url('admin/subjects/'.$s->id) }}"
method="POST"
class="flex-1"
onsubmit="return confirm('Hapus mata pelajaran ini?')">

@csrf
@method('DELETE')

<button
class="w-full px-2 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200">
Hapus
</button>

</form>

</div>

</div>

@endforeach

</div>

</div>

</div>



{{-- MODAL TAMBAH --}}
<div id="modal-add" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">

<div class="bg-white p-6 rounded-lg shadow w-full max-w-sm">

<h3 class="text-sm font-semibold mb-4">
Tambah Mata Pelajaran
</h3>

<form action="{{ route('admin.subjects.store') }}" method="POST">
@csrf

<input
name="nama_mapel"
class="w-full border rounded px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-primary"
placeholder="Nama Mapel"
required>

<div class="flex justify-end gap-2">

<button
type="button"
onclick="closeAddModal()"
class="border px-3 py-1 rounded text-xs hover:bg-gray-100">
Batal
</button>

<button
class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
Simpan
</button>

</div>

</form>

</div>

</div>



{{-- MODAL EDIT --}}
<div id="modal-edit" class="hidden fixed inset-0 flex items-center justify-center bg-black/40 z-50">

<div class="bg-white p-6 rounded-lg shadow w-full max-w-sm">

<h3 class="text-sm font-semibold mb-4">
Edit Mata Pelajaran
</h3>

<form id="edit-form" method="POST">

@csrf
@method('PUT')

<input
name="nama_mapel"
id="edit-nama"
class="w-full border rounded px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-primary"
required>

<div class="flex justify-end gap-2">

<button
type="button"
onclick="closeEditModal()"
class="border px-3 py-1 rounded text-xs hover:bg-gray-100">
Batal
</button>

<button
class="bg-primary text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
Update
</button>

</div>

</form>

</div>

</div>



<script>

function openAddModal(){
document.getElementById('modal-add').classList.remove('hidden')
}

function closeAddModal(){
document.getElementById('modal-add').classList.add('hidden')
}

function openEditModal(data){

document.getElementById('modal-edit').classList.remove('hidden')

document.getElementById('edit-nama').value = data.nama_mapel

document.getElementById('edit-form').action = '/admin/subjects/' + data.id

}

function closeEditModal(){
document.getElementById('modal-edit').classList.add('hidden')
}

</script>

@endsection