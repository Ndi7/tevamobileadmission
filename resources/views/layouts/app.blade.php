<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<title>Bimbel TEMA - {{ $title ?? 'Admin Panel' }}</title>

<link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
darkMode: 'class',
theme:{
extend:{
colors:{
primary:'#006FB8'
}
}
}
}
</script>

<script>
if(localStorage.getItem('theme') === 'dark'){
document.documentElement.classList.add('dark')
}
</script>

<style>

/* Background */

body{
background:#f8fafc;
font-family:ui-sans-serif,system-ui;
margin:0;
color:#0f172a;
}

.dark body{
background:#0f172a;
color:#e2e8f0;
}


/* Header */

.header{
width:100%;
background:white;
border-bottom:1px solid #e5e7eb;
}

.dark .header{
background:#1e293b;
border-color:#334155;
}


/* Layout */

.layout{
display:flex;
min-height:calc(100vh - 60px);
}


/* Sidebar */

.sidebar{
width:260px;
background:white;
border-right:1px solid #e5e7eb;
padding:24px;
}

.dark .sidebar{
background:#1e293b;
border-color:#334155;
}


/* Menu */

.menu-item{
display:block;
padding:10px 14px;
border-radius:8px;
font-size:14px;
color:#334155;
transition:.15s;
}

.menu-item:hover{
background:#f1f5f9;
}

.menu-active{
background:#eff6ff;
border-left:3px solid #006FB8;
color:#006FB8;
font-weight:600;
}


/* Content */

.main{
flex:1;
padding:24px 32px;
}


/* Card */

.card{
background:white;
border:1px solid #e5e7eb;
border-radius:10px;
padding:20px;
}

.dark .card{
background:#1e293b;
border-color:#334155;
}


/* Table */

table{
min-width:900px;
}


/* Scroll */

.scroll-x{
overflow-x:auto;
}

.scroll-x::-webkit-scrollbar{
height:8px;
}

.scroll-x::-webkit-scrollbar-thumb{
background:#cbd5e1;
border-radius:20px;
}

</style>

</head>


<body>


<!-- HEADER -->

<header class="header">

<div class="flex justify-between items-center px-8 py-3">

<div class="flex items-center gap-3">

<img src="{{ asset('assets/images/logo.png') }}" class="w-9">

<span class="font-semibold text-gray-800">
Bimbel TEMA
</span>

</div>


<div class="flex items-center gap-6 text-sm text-gray-600">
    <button onclick="toggleDark()" class="px-2 py-1 rounded hover:bg-gray-100">
    🌙
    </button>

<a href="{{ route('pengaturan') }}" class="hover:text-primary">
Pengaturan
</a>

<span class="text-gray-300">|</span>

<span>
{{ session('adminName', $adminName ?? 'Admin') }}
</span>

<form action="{{ route('logout') }}" method="GET">
<button class="text-red-500 hover:text-red-700">
Logout
</button>
</form>

</div>

</div>

</header>



<!-- LAYOUT -->

<div class="layout">


<!-- SIDEBAR -->

<aside class="sidebar">

<nav class="space-y-6 text-sm">


<div>

<p class="text-xs text-gray-400 uppercase mb-2">
Menu
</p>

<a href="/admin/dashboard"
class="menu-item {{ request()->is('dashboard') ? 'menu-active' : '' }}">
Dashboard
</a>

</div>


<div>

<p class="text-xs text-gray-400 uppercase mb-2">
Manajemen
</p>

<a href="/admin/pendaftaran"
class="menu-item flex justify-between items-center {{ request()->is('pendaftaran*') ? 'menu-active' : '' }}">

<span>Pendaftaran</span>

@if($jumlahMenunggu > 0)
<span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
{{ $jumlahMenunggu }}
</span>
@endif

</a>

<a href="/admin/siswa-aktif"
class="menu-item {{ request()->is('siswa-aktif*') ? 'menu-active' : '' }}">
Data Siswa
</a>

<a href="/admin/pembayaran"
class="menu-item flex justify-between items-center {{ request()->is('pembayaran*') ? 'menu-active' : '' }}">

<span>Pembayaran</span>

@if($jumlahPembayaranPending > 0)
<span class="bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">
{{ $jumlahPembayaranPending }}
</span>
@endif

</a>

<a href="/admin/pengaturan-pembayaran"
class="menu-item {{ request()->is('pengaturan-pembayaran*') ? 'menu-active' : '' }}">
Metode Pembayaran
</a>

</div>


<div>

<p class="text-xs text-gray-400 uppercase mb-2">
Kursus
</p>

<a href="/admin/kelas"
class="menu-item {{ request()->is('kelas*') ? 'menu-active' : '' }}">
Kelas
</a>

<a href="/admin/subjects"
class="menu-item {{ request()->is('subjects*') ? 'menu-active' : '' }}">
Mata Pelajaran
</a>

<a href="/admin/programs"
class="menu-item {{ request()->is('programs*') ? 'menu-active' : '' }}">
Program
</a>

<!-- <a href="/admin/fees"
class="menu-item {{ request()->is('fees*') ? 'menu-active' : '' }}">
Biaya
</a> -->

</div>


<div>

<p class="text-xs text-gray-400 uppercase mb-2">
Lainnya
</p>

<a href="/admin/pengumuman"
class="menu-item {{ request()->is('pengumuman*') ? 'menu-active' : '' }}">
Pengumuman
</a>

<a href="/admin/laporan"
class="menu-item {{ request()->is('laporan*') ? 'menu-active' : '' }}">
Laporan
</a>

</div>


</nav>

</aside>



<!-- CONTENT -->

<main class="main">

<div class="card">

<div class="scroll-x">

@yield('content')

</div>

</div>

</main>


</div>

<script>

function toggleDark(){

document.documentElement.classList.toggle('dark')

if(document.documentElement.classList.contains('dark')){
localStorage.setItem('theme','dark')
}else{
localStorage.setItem('theme','light')
}

}

</script>
</body>
</html>