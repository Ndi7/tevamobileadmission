@php
    function parseMapel($data) {
        if (!$data || $data == "[]") return [];

        $result = [];
        $decoded = json_decode($data, true);

        if (is_array($decoded)) {
            foreach ($decoded as $item) {
                if (isset($item['nama'])) {
                    $result[] = $item['nama'];
                }
            }
        }

        return $result;
    }

    $mapel = array_merge(
        parseMapel($mapelWajib),
        parseMapel($mapelReguler),
        parseMapel($mapelEkskul)
    );

    function getStatusText($paymentStatus, $status) {
        return match($paymentStatus) {
            'ditolak' => 'DITOLAK',
            'menunggu' => 'MENUNGGU',
            'diterima' => 'DITERIMA',
            default => $status
        };
    }

    function getStatusColor($paymentStatus) {
        return match($paymentStatus) {
            'ditolak' => 'bg-red-500',
            'menunggu' => 'bg-orange-400',
            'diterima' => 'bg-green-500',
            default => 'bg-gray-400'
        };
    }
@endphp

<div class="bg-[#2E86C1] text-white p-4 rounded-2xl shadow-md mb-4">

    {{-- HEADER --}}
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 bg-white rounded-full overflow-hidden flex items-center justify-center">
            @if($foto)
                <img src="{{ asset('uploads/' . basename($foto)) }}" class="w-full h-full object-cover">
            @else
                <span class="text-blue-500 text-xl">👤</span>
            @endif
        </div>

        <div class="flex-1">
            <div class="font-bold">{{ $nama }}</div>
            <div class="text-sm text-white/80">{{ $kelas }}</div>
            <div class="text-xs text-white/60">{{ $tanggal }}</div>
        </div>

        <div class="px-3 py-1 text-xs font-bold rounded-full {{ getStatusColor($paymentStatus) }}">
            {{ getStatusText($paymentStatus, $status) }}
        </div>
    </div>

    {{-- DIVIDER --}}
    <div class="border-t border-white/30 my-3"></div>

    {{-- CONTENT --}}
    <div class="flex gap-4">
        {{-- MAPEL --}}
        <div class="flex-[2]">
            <div class="font-semibold">Mata Pelajaran</div>
            <div class="text-sm text-white/80 mt-1 space-y-1">
                @forelse($mapel as $m)
                    <div>• {{ $m }}</div>
                @empty
                    <div>-</div>
                @endforelse
            </div>
        </div>

        {{-- BIAYA --}}
        <div class="flex-1">
            <div class="font-semibold">Biaya Les</div>
            <div class="text-sm font-bold text-white/80 mt-1">
                Rp {{ number_format($biaya, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- BUTTON --}}
    @if($status == "DITERIMA")
        <div class="mt-4">
            <button
                class="w-full py-2 rounded-full font-bold
                {{ $paymentStatus ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }}"
                {{ $paymentStatus ? 'disabled' : '' }}
            >
                @if(!$paymentStatus)
                    Lanjutkan Pembayaran
                @elseif($paymentStatus == "menunggu")
                    Menunggu Verifikasi
                @elseif($paymentStatus == "diterima")
                    Pembayaran Berhasil
                @elseif($paymentStatus == "ditolak")
                    Pembayaran Ditolak
                @endif
            </button>
        </div>
    @endif

</div>