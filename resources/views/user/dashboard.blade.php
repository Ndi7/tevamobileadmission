@extends('user.layouts.app', [
    'title' => 'Dashboard',
    'mode' => 'dashboard'
])

@section('content')

<style>
.skeleton {
  position: relative;
  overflow: hidden;
  background: #e5e7eb;
}

.skeleton::after {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
  animation: shimmer 1.2s infinite;
}

@keyframes shimmer {
  100% { left: 100%; }
}
</style>

@php
function parseMapel($data) {
    if (!$data) return [];

    $decoded = is_string($data) ? json_decode($data, true) : $data;

    $result = [];

    if (is_array($decoded)) {
        foreach ($decoded as $item) {

            if (is_array($item)) {
                if (isset($item['nama_mapel'])) {
                    $result[] = $item['nama_mapel'];
                } elseif (isset($item['mapel']['nama_mapel'])) {
                    $result[] = $item['mapel']['nama_mapel'];
                } elseif (isset($item['nama'])) {
                    $result[] = $item['nama'];
                } else {
                    $result[] = 'Mapel';
                }
            } else {
                $result[] = 'Mapel';
            }

        }
    }

    return $result;
}

function getStatusText($payment, $status) {
    if ($payment == 'ditolak') return 'DITOLAK';
    if ($payment == 'menunggu') return 'MENUNGGU';
    if ($payment == 'diterima') return 'DITERIMA';
    return strtoupper($status ?? 'MENUNGGU');
}

function getStatusColor($payment, $status) {
    if ($payment == 'diterima') return 'bg-green-500';
    if ($payment == 'ditolak') return 'bg-red-500';
    if (($status ?? '') == 'DITERIMA') return 'bg-green-500';
    return 'bg-orange-400';
}
@endphp

<!-- SKELETON -->
<div id="skeleton" class="max-w-7xl mx-auto px-6 py-8">

    <div class="border border-gray-200 rounded-xl p-5 flex justify-between items-center mb-8 bg-white">
        <div class="space-y-2">
            <div class="skeleton h-5 w-40 rounded"></div>
            <div class="skeleton h-4 w-56 rounded"></div>
        </div>
        <div class="skeleton w-20 h-20 rounded"></div>
    </div>

    <div class="skeleton h-5 w-32 mb-5 rounded"></div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @for($i = 0; $i < 6; $i++)
        <div class="bg-white border border-gray-200 rounded-xl p-4">

            <div class="flex items-start gap-3 mb-3">
                <div class="skeleton w-11 h-11 rounded-full"></div>

                <div class="flex-1 space-y-2">
                    <div class="skeleton h-4 w-32 rounded"></div>
                    <div class="skeleton h-3 w-20 rounded"></div>
                    <div class="skeleton h-3 w-24 rounded"></div>
                </div>

                <div class="skeleton h-5 w-16 rounded-full"></div>
            </div>

            <div class="border-t my-3"></div>

            <div class="flex justify-between gap-4">
                <div class="flex-1 space-y-2">
                    <div class="skeleton h-3 w-24 rounded"></div>
                    <div class="skeleton h-3 w-20 rounded"></div>
                    <div class="skeleton h-3 w-28 rounded"></div>
                </div>

                <div class="w-[90px] space-y-2 text-right">
                    <div class="skeleton h-3 w-16 rounded ml-auto"></div>
                    <div class="skeleton h-4 w-20 rounded ml-auto"></div>
                </div>
            </div>

            <div class="mt-4 skeleton h-8 w-full rounded-full"></div>

        </div>
        @endfor
    </div>
</div>

<!-- CONTENT ASLI (TIDAK DIUBAH) -->
<div id="content" class="hidden max-w-7xl mx-auto px-6 py-8">

    <!-- WELCOME -->
    <div class="border border-gray-200 rounded-xl p-5 flex justify-between items-center mb-8 bg-white">
        <div>
            <h2 class="text-xl font-semibold text-[#006FB8]">Selamat datang</h2>
            <p class="text-sm text-gray-500">Majukan generasi muda.</p>
        </div>
        <img src="{{ asset('assets/images/edu_icon.png') }}" class="w-20 h-20 object-contain">
    </div>

    <h3 class="font-semibold text-gray-700 mb-5">Status Siswa</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

        @forelse($pendaftar as $siswa)

            @php
                $paymentStatus = $siswa->payment->status ?? null;

                $mapel = array_merge(
                    parseMapel($siswa->mapel_wajib ?? null),
                    parseMapel($siswa->mapel_reguler ?? null),
                    parseMapel($siswa->mapel_ekskul ?? null)
                );
            @endphp

            <div class="bg-[#006FB8] rounded-xl p-4 text-white shadow-sm hover:shadow-md hover:-translate-y-[2px] transition duration-200">

                <div class="flex items-start gap-3">

                    <div class="w-11 h-11 bg-white rounded-full overflow-hidden flex items-center justify-center shrink-0">
                        @if(!empty($siswa->foto))
                            <img src="{{ asset('uploads/' . basename($siswa->foto)) }}"
                                 class="w-full h-full object-cover">
                        @else
                            <svg class="w-6 h-6 text-[#006FB8]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4.4 0-8 2-8 4v2h16v-2c0-2-3.6-4-8-4z"/>
                            </svg>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-semibold leading-tight truncate">
                            {{ $siswa->nama ?? '-' }}
                        </p>
                        <p class="text-sm text-white/80">
                            {{ $siswa->kelas ?? '-' }}
                        </p>

                        <p class="text-xs text-white/60 mt-1">
                            {{ $siswa->created_at 
                                ? \Carbon\Carbon::parse($siswa->created_at)->format('d M Y, H:i') 
                                : '-' }} WIB
                        </p>
                    </div>

                    <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full
                        {{ getStatusColor($paymentStatus, $siswa->status ?? null) }}">
                        {{ getStatusText($paymentStatus, $siswa->status ?? null) }}
                    </span>
                </div>

                <div class="border-t border-white/30 my-3"></div>

                <div class="flex justify-between gap-4 text-sm">

                    <div class="flex-1">
                        <p class="font-semibold mb-1">Mata Pelajaran</p>

                        <div class="text-white/80 space-y-1 text-xs leading-tight">
                            @forelse($mapel as $m)
                                <div>• {{ $m }}</div>
                            @empty
                                <div>-</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="w-[90px] text-right">
                        <p class="font-semibold text-xs">Biaya</p>
                        <p class="font-bold mt-1 text-white/90 text-sm">
                            Rp {{ number_format((int) ($siswa->total_harga ?? 0), 0, ',', '.') }}
                        </p>
                    </div>

                </div>

                @if(strtoupper($siswa->status ?? '') == "DITERIMA")
                    <div class="mt-4">
                        <a href="{{ route('user.pembayaran', [
                                'uuid' => $siswa->uuid,
                                'jumlah' => $siswa->biaya
                            ]) }}"
                            class="block text-center w-full py-2 rounded-full text-sm font-semibold transition active:scale-95
                            {{ $paymentStatus ? 'bg-gray-400 cursor-not-allowed pointer-events-none' : 'bg-green-500 hover:bg-green-600' }}">
                            
                                @if(!$paymentStatus)
                                    Lanjutkan Pembayaran
                                @elseif($paymentStatus == "menunggu")
                                    Menunggu Verifikasi
                                @elseif($paymentStatus == "diterima")
                                    Pembayaran Berhasil
                                @elseif($paymentStatus == "ditolak")
                                    Pembayaran Ditolak
                                @endif

                        </a>
                    </div>
                @endif
            </div>

        @empty
            <p class="col-span-full text-center text-gray-500 py-10">
                Belum ada pendaftaran siswa
            </p>
        @endforelse

    </div>
</div>

<a href="/user/ketentuan"
   class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-[#006FB8]
          flex items-center justify-center text-white shadow-lg hover:scale-105 transition">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4.4 0-8 2-8 4v2h16v-2c0-2-3.6-4-8-4z"/>
    </svg>
</a>

<script>
window.onload = () => {
    setTimeout(() => {
        document.getElementById('skeleton').style.display = 'none'
        document.getElementById('content').classList.remove('hidden')
    }, 700)
}
</script>

@endsection