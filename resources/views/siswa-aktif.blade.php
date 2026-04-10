@extends('layouts.app')

@section('content')

<h2 class="text-lg font-semibold text-primary mb-4">
Data Siswa Aktif
</h2>

<div class="bg-white rounded-lg shadow-sm border overflow-x-auto">

<table class="min-w-full text-sm">

<thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
<tr>

<th class="px-3 py-2 whitespace-nowrap">Nama</th>
<th class="px-3 py-2 whitespace-nowrap text-center">Kelas</th>
<th class="px-3 py-2 whitespace-nowrap text-center">Program</th>
<th class="px-3 py-2 whitespace-nowrap text-center">Status</th>
<th class="px-3 py-2 whitespace-nowrap text-center">Aksi</th>

</tr>
</thead>

<tbody class="divide-y">

@forelse($students as $data)

<tr class="hover:bg-gray-50 transition">

<td class="px-3 py-2 font-medium">
{{ $data->nama ?? '-' }}
</td>

<td class="px-3 py-2 text-center whitespace-nowrap">
{{ $data->kelas ?? '-' }}
</td>

<td class="px-3 py-2 text-center">
<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">

@php
    $mapel = [];

    foreach (['mapel_wajib', 'mapel_reguler', 'mapel_ekskul'] as $field) {
        if (!empty($data->$field)) {
            $decoded = json_decode($data->$field, true);

            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    if (isset($item['nama'])) {
                        $mapel[] = $item['nama'];
                    }
                }
            }
        }
    }
@endphp

{{ !empty($mapel) ? implode(', ', $mapel) : '-' }}

</span>
</td>

<td class="px-3 py-2 text-center">
<span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
Aktif
</span>
</td>

<td class="px-3 py-2 text-center">
<button class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs transition">
Edit
</button>
</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center py-4 text-gray-400">
Belum ada siswa aktif
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

@endsection