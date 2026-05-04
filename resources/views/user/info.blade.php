@extends('user.layouts.app')

@section('content')

<style>
:root{
    --primary:#006FB8;
}

/* hover sama kayak dashboard */
.card-hover{
    transition: all .2s ease;
}
.card-hover:hover{
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
}

/* skeleton */
.skeleton{
    background: linear-gradient(
        90deg,
        #e5e7eb 25%,
        #f3f4f6 37%,
        #e5e7eb 63%
    );
    background-size: 400% 100%;
    animation: shimmer 1.4s ease infinite;
}

@keyframes shimmer{
    0%{ background-position: 100% 0 }
    100%{ background-position: -100% 0 }
}
</style>

<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- TITLE (GANTI WELCOME) -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mt-1">
            Informasi umum terkait aplikasi dan layanan
        </p>
    </div>

    <!-- CONTENT -->
    <div class="max-w-2xl space-y-4">

        <!-- SKELETON -->
        <div id="skeleton">
            <div class="border border-gray-200 rounded-xl p-5 mb-4 bg-white">
                <div class="skeleton h-4 w-40 rounded mb-3"></div>
                <div class="skeleton h-3 w-full rounded mb-2"></div>
                <div class="skeleton h-3 w-5/6 rounded"></div>
            </div>

            <div class="border border-gray-200 rounded-xl p-5 mb-4 bg-white">
                <div class="skeleton h-4 w-32 rounded mb-3"></div>
                <div class="skeleton h-3 w-20 rounded"></div>
            </div>

            <div class="border border-gray-200 rounded-xl p-5 bg-white">
                <div class="skeleton h-4 w-28 rounded mb-3"></div>
                <div class="skeleton h-3 w-60 rounded"></div>
            </div>
        </div>

        <!-- REAL CONTENT -->
        <div id="realContent" class="hidden space-y-4">

            <!-- CARD -->
            <div class="border border-gray-200 rounded-xl p-5 bg-white card-hover">
                <h3 class="font-semibold text-gray-800 mb-2">
                    Tentang Aplikasi
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Aplikasi ini dibuat untuk membantu proses belajar mengajar 
                    secara lebih efektif, terstruktur, dan mudah diakses oleh siswa maupun orang tua.
                </p>
            </div>

            <!-- CARD -->
            <div class="border border-gray-200 rounded-xl p-5 bg-white card-hover">
                <h3 class="font-semibold text-gray-800 mb-2">
                    Versi Aplikasi
                </h3>
                <p class="text-sm text-gray-600">
                    v1.0.0
                </p>
            </div>

            <!-- CARD -->
            <div class="border border-gray-200 rounded-xl p-5 bg-white card-hover">
                <h3 class="font-semibold text-gray-800 mb-2">
                    Kontak
                </h3>
                <p class="text-sm text-gray-600">
                    Email: support@contoh.com
                </p>
            </div>

        </div>

    </div>

</div>

<script>
// loading smooth
setTimeout(()=>{
    document.getElementById('skeleton').style.display = 'none'
    document.getElementById('realContent').classList.remove('hidden')
}, 800)
</script>

@endsection