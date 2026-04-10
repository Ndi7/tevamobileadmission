@extends('layouts.app')

@section('content')

<div x-data="{ openModal:false, editId:null, title:'', body:'' }">

    <div class="flex justify-between items-center mb-5">
        <h2 class="text-xl font-semibold text-primary">
            Pengumuman
        </h2>

        <button
            @click="
                openModal=true;
                editId=null;
                title='';
                body='';
            "
            class="px-4 py-2 bg-primary text-white rounded-md text-sm hover:bg-blue-700">
            + Buat Pengumuman
        </button>
    </div>


    <div class="bg-white rounded-lg shadow-sm border">

        <ul class="divide-y">

            @forelse($data as $item)

            <li class="p-4 flex justify-between items-start">

                <div class="max-w-xl">

                    <div class="font-semibold text-gray-800">
                        {{ $item->title }}
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                    </div>

                    <p class="mt-2 text-sm text-gray-600">
                        {{ $item->body }}
                    </p>

                </div>

                <div class="flex gap-2">

                    <!-- ✅ EDIT FIX (AMAN DARI ERROR QUOTES) -->
                    <button 
                        @click='
                            openModal=true;
                            editId={{ $item->id }};
                            title=@json($item->title);
                            body=@json($item->body);
                        '
                        class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200">
                        ✏️ Edit
                    </button>

                    <!-- ✅ HAPUS FIX -->
                    <form action="/admin/pengumuman/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus pengumuman?')">
                        @csrf
                        @method('DELETE')

                        <button class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">
                            🗑 Hapus
                        </button>
                    </form>

                </div>

            </li>

            @empty

            <li class="p-4 text-gray-400">
                Belum ada pengumuman
            </li>

            @endforelse

        </ul>

    </div>



    {{-- MODAL --}}
    <div
        x-show="openModal"
        x-transition
        class="fixed inset-0 flex items-center justify-center z-50">

        <div
            @click="openModal=false"
            class="absolute inset-0 bg-black/40">
        </div>

        <div
            @click.stop
            class="bg-white rounded-lg p-6 w-full max-w-lg z-10 shadow-lg">

            <h3 class="text-lg font-semibold mb-4">
                Buat Pengumuman
            </h3>

            <!-- ✅ FORM FIX (CREATE + UPDATE) -->
            <form 
                :action="editId ? '/admin/pengumuman/' + editId : '/admin/pengumuman'" 
                method="POST">

                @csrf

                <!-- kalau edit -->
                <template x-if="editId">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <label class="block text-sm mb-1">
                    Judul
                </label>

                <input
                    name="title"
                    x-model="title"
                    required
                    class="w-full border rounded-md p-2 mb-3 text-sm">


                <label class="block text-sm mb-1">
                    Isi
                </label>

                <textarea
                    name="body"
                    x-model="body"
                    required
                    rows="4"
                    class="w-full border rounded-md p-2 mb-4 text-sm"></textarea>


                <div class="flex justify-end gap-2">

                    <button
                        type="button"
                        @click="openModal=false"
                        class="px-3 py-1 border rounded text-sm">
                        Batal
                    </button>

                    <button
                        class="px-4 py-1 bg-primary text-white rounded text-sm hover:bg-blue-700">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection