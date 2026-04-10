@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4 text-primary">Pengaturan Akun Admin</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

{{-- WRAPPER KIRI & KANAN --}}
<div class="flex gap-6 items-start">

    {{-- FORM KIRI --}}
    <div class="bg-white p-6 shadow rounded w-full max-w-md">
        <form action="{{ route('pengaturan') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block mb-1 font-medium">Nama Admin</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                       class="border rounded p-2 w-full focus:ring-2 focus:ring-primary focus:outline-none" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" value="{{ $admin->email }}"
                       class="border rounded p-2 w-full bg-gray-100" readonly>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Password Baru (opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ubah"
                       class="border rounded p-2 w-full focus:ring-2 focus:ring-primary focus:outline-none">
            </div>

            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- DIV KANAN (TAMPILAN SAMA) --}}
    <div class="bg-white p-6 shadow rounded w-56 text-center">
        <h2 class="text-sm font-semibold text-gray-700 mb-2">
            Tambah Admin
        </h2>
        <p class="text-xs text-gray-500">
            Fitur ini akan tersedia<br>
            pada update berikutnya.
        </p>

        <div class="mt-4 text-xs text-gray-400 italic">
            (Coming Soon)
        </div>
    </div>

</div>
@endsection