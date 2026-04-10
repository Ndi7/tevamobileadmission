@extends('layouts.app')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-primary">Kelola Biaya</h2>
        <button onclick="openFeeModal()" class="px-3 py-1 bg-primary text-white rounded">
            + Tambah Biaya
        </button>
    </div>

    {{-- 🔔 Notifikasi --}}
    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">
        {{ session('success') }}
    </div>
    @endif

    {{-- 🔔 Error --}}
    @if($errors->any())
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- TABEL DATA --}}
    <div class="bg-white rounded shadow p-4">
        <table class="w-full text-sm">
            <thead class="text-left text-gray-600 border-b">
                <tr>
                    <th class="p-2">Jenjang</th>
                    <th class="p-2">Tipe</th>
                    <th class="p-2">Biaya</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $b)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $b->jenjang }}</td>
                    <td class="p-2 capitalize">{{ $b->tipe }}</td>
                    <td class="p-2">Rp {{ number_format($b->biaya, 0, ',', '.') }}</td>
                    <td class="p-2 text-center flex justify-center gap-1">
                        <!-- Tombol Edit -->
                        <button onclick="editFee({{ $b->id }}, '{{ $b->jenjang }}', '{{ $b->tipe }}', '{{ $b->biaya }}')" 
                            class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 text-xs">
                            ✏️ Edit
                        </button>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('admin.biaya.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus biaya ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs">
                                🗑 Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-500 py-3">Belum ada data biaya.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL TAMBAH / EDIT --}}
    <div id="modal-fee" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-black/40 absolute inset-0"></div>
        <div class="bg-white rounded p-6 z-10 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4" id="modal-title">Tambah Biaya</h3>

            <!-- ✅ FORM DITAMBAHKAN DI SINI -->
            <form action="{{ route('biaya.store') }}" method="POST" id="fee-form">
                @csrf
                <input type="hidden" id="method-input" name="_method" value="POST">

                <label class="block mb-2">Jenjang</label>
                <select name="jenjang" id="fee-jenjang" class="w-full border rounded p-2 mb-3" required>
                    <option value="">Pilih Jenjang</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="Umum">Umum</option>
                </select>

                <label class="block mb-2">Tipe</label>
                <select name="tipe" id="fee-tipe" class="w-full border rounded p-2 mb-3" required>
                    <option value="">Pilih Tipe</option>
                    <option value="wajib">Wajib</option>
                    <option value="reguler">Reguler</option>
                    <option value="ekskul">Ekskul</option>
                </select>

                <label class="block mb-2">Nominal (Rp)</label>
                <input name="biaya" id="fee-amount" class="w-full border rounded p-2 mb-3" type="number" required>

                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" onclick="closeFeeModal()" class="px-3 py-1 border rounded">Batal</button>
                    <button class="px-3 py-1 bg-primary text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT MODAL --}}
<script>
function openFeeModal() {
    document.getElementById('modal-fee').classList.remove('hidden');
    document.getElementById('modal-title').textContent = 'Tambah Biaya';
    document.getElementById('fee-form').action = "{{ route('biaya.store') }}";
    document.getElementById('method-input').value = 'POST';
    document.getElementById('fee-jenjang').value = '';
    document.getElementById('fee-tipe').value = '';
    document.getElementById('fee-amount').value = '';
}

function closeFeeModal() {
    document.getElementById('modal-fee').classList.add('hidden');
}

function editFee(id, jenjang, tipe, biaya) {
    document.getElementById('modal-fee').classList.remove('hidden');
    document.getElementById('modal-title').textContent = 'Edit Biaya';
    document.getElementById('fee-form').action = `/admin/biaya/${id}`;
    document.getElementById('method-input').value = 'PUT';
    document.getElementById('fee-jenjang').value = jenjang;
    document.getElementById('fee-tipe').value = tipe;
    document.getElementById('fee-amount').value = biaya;
}
</script>
@endsection
