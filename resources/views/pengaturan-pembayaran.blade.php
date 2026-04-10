@extends('layouts.app')

@section('content')

<h2 class="text-lg font-semibold text-primary mb-4">
Pengaturan Metode Pembayaran
</h2>

<div class="bg-white rounded-lg shadow-sm border p-6 max-w-3xl">

@if(session('success'))
<div class="mb-4 text-green-600 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- ========================= --}}
{{-- FORM BANK --}}
{{-- ========================= --}}
<form action="/admin/pengaturan-pembayaran" method="POST">
@csrf

<div class="mb-8">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">
        Transfer Bank
    </h3>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="text-xs text-gray-500">Bank</label>
            <input type="text" name="bank"
                value="{{ $setting->bank ?? '' }}"
                class="w-full border rounded-md px-3 py-2 text-sm">
        </div>

        <div>
            <label class="text-xs text-gray-500">No. Rekening</label>
            <input type="text" name="rekening"
                value="{{ $setting->rekening ?? '' }}"
                class="w-full border rounded-md px-3 py-2 text-sm">
        </div>

        <div>
            <label class="text-xs text-gray-500">Pemilik</label>
            <input type="text" name="pemilik"
                value="{{ $setting->pemilik ?? '' }}"
                class="w-full border rounded-md px-3 py-2 text-sm">
        </div>
    </div>

    <div class="pt-4 text-right">
        <button type="submit"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
            Simpan Bank
        </button>
    </div>
</div>

</form>

{{-- ========================= --}}
{{-- FORM QRIS --}}
{{-- ========================= --}}
<form action="/admin/update-qris" method="POST" enctype="multipart/form-data">
@csrf

<div>
    <h3 class="text-sm font-semibold text-gray-700 mb-3">
        QRIS
    </h3>

    <div class="grid grid-cols-2 gap-6 items-start">

        {{-- PREVIEW --}}
        <div>
            @if(!empty($setting->qris))
            <p class="text-xs text-gray-500 mb-2">QRIS Saat Ini</p>

            <div class="border p-3 rounded w-fit">
                <img src="{{ asset('storage/'.$setting->qris) }}" class="w-40">
            </div>
            @endif
        </div>

        {{-- UPLOAD --}}
        <div>
            <input type="file" name="qris" id="qrisInput"
                class="w-full border rounded px-3 py-2 text-sm">

            <p class="text-xs text-gray-400 mt-1">
                Upload QRIS baru
            </p>

            <div id="previewContainer" class="hidden mt-3">
                <p class="text-xs text-blue-500 mb-1">Preview Baru</p>
                <img id="previewImage" class="w-40 border rounded">
            </div>
        </div>

    </div>

    <div class="flex justify-between mt-4">

        {{-- HAPUS (pakai form terpisah) --}}
        @if(!empty($setting->qris))
        <button type="submit" form="formHapusQris"
            class="text-red-600 text-sm hover:underline">
            Hapus QRIS
        </button>
        @endif

        {{-- SIMPAN --}}
        <button type="submit"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
            Simpan QRIS
        </button>

    </div>

</div>

</form>

{{-- FORM HAPUS QRIS (TIDAK NESTED) --}}
<form id="formHapusQris" action="/admin/hapus-qris" method="POST">
@csrf
</form>

</div>

{{-- JS PREVIEW --}}
<script>
document.getElementById('qrisInput').addEventListener('change', function(e) {
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(ev) {
            document.getElementById('previewImage').src = ev.target.result;
            document.getElementById('previewContainer').classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    }
});
</script>

@endsection