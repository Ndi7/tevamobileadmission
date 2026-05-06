<!-- NAVBAR -->
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-2 flex items-center justify-between">
        <div class="font-semibold text-gray-800">
            BUC TEVA CENTRE
        </div>

        <div class="flex gap-6 text-sm">
            <a href="{{ route('user.dashboard') }}"
               class="{{ request()->routeIs('user.dashboard') ? 'text-[#006FB8] font-semibold' : 'text-gray-600 hover:text-[#006FB8]' }}">
                Home
            </a>

            <a href="/user/info"
               class="{{ request()->is('user/info') ? 'text-[#006FB8] font-semibold' : 'text-gray-600 hover:text-[#006FB8]' }}">
                Info
            </a>

            <a href="/user/profile"
               class="{{ request()->is('user/profile') ? 'text-[#006FB8] font-semibold' : 'text-gray-600 hover:text-[#006FB8]' }}">
                Profil
            </a>
        </div>
    </div>
</nav>


<!-- HEADER -->
<div class="bg-[#006FB8] px-6 py-3">
    <div class="max-w-7xl mx-auto flex justify-between items-center text-white">

        <!-- KIRI -->
        @if(isset($headerType) && $headerType == 'info')
            <h1 class="text-lg font-medium">
                Informasi
            </h1>
        @else
            <div class="flex items-center gap-3">

                @if(isset($showProfile) && $showProfile)
                    <div class="w-10 h-10 rounded-full bg-white overflow-hidden flex items-center justify-center">
                        @if($user->photo)
                            <img src="{{ asset('uploads/'.$user->photo) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-5 h-5 text-[#006FB8]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4.4 0-8 2-8 4v2h16v-2c0-2-3.6-4-8-4z"/>
                            </svg>
                        @endif
                    </div>

                    <span class="font-medium">Hi, {{ $user->name }}</span>
                @else
                    <span class="font-semibold text-lg">
                        {{ $title ?? 'Halaman' }}
                    </span>
                @endif

            </div>
        @endif


        <!-- NOTIF (HANYA JIKA BUKAN HALAMAN INFO) -->
        @if((!isset($showNotif) || $showNotif) && (!isset($headerType) || $headerType != 'info'))
        <button onclick="openNotif()" class="relative">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 24c1.3 0 2.4-1.1 2.4-2.4h-4.8c0 1.3 1.1 2.4 2.4 2.4zm6.7-6v-5.6c0-3.2-2.1-6-5-6.8v-.8c0-.9-.7-1.6-1.6-1.6s-1.6.7-1.6 1.6v.8c-2.9.8-5 3.6-5 6.8V18L3 19.6v.8h18v-.8L18.7 18z"/>
            </svg>

            <!-- BADGE -->
            <span id="notifBadge"
                  class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 rounded-full hidden">
            </span>
        </button>
        @endif

    </div>
</div>


<!-- ================= NOTIF SLIDE ================= -->

<!-- OVERLAY -->
<div id="notifOverlay"
     class="fixed inset-0 bg-black/30 hidden z-40"
     onclick="closeNotif()">
</div>

<!-- PANEL -->
<div id="notifPanel"
     class="fixed top-0 right-0 h-full w-[350px] bg-white shadow-xl 
            transform translate-x-full transition duration-300 
            z-50 flex flex-col">

    <!-- HEADER -->
    <div class="p-4 border-b font-semibold flex justify-between items-center">
        Notifikasi
        <button onclick="closeNotif()">✕</button>
    </div>

    <!-- CONTENT -->
    <div id="notifContent" class="flex-1 overflow-y-auto p-4 space-y-3">

        <!-- LOADING -->
        <div id="notifLoading" class="text-center text-sm text-gray-500">
            Memuat...
        </div>

    </div>
</div>