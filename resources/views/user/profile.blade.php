@extends('user.layouts.app', [
    'title' => 'Profil',
    'mode' => 'profile'
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

<!-- SKELETON -->
<div id="skeleton" class="max-w-xl mx-auto px-6 py-10">

  <!-- HEADER -->
  <div class="mb-10 text-center">
    <div class="skeleton h-6 w-32 mx-auto rounded"></div>
  </div>

  <!-- CARD -->
  <div class="bg-white border rounded-lg p-6 text-center">

    <div class="flex justify-center">
      <div class="skeleton w-24 h-24 rounded-full"></div>
    </div>

    <div class="mt-4 space-y-2">
      <div class="skeleton h-4 w-40 mx-auto rounded"></div>
      <div class="skeleton h-3 w-52 mx-auto rounded"></div>
    </div>

  </div>

  <!-- MENU -->
  <div class="mt-6 bg-white border rounded-lg divide-y">

    <div class="px-5 py-4 flex justify-between">
      <div class="skeleton h-4 w-24 rounded"></div>
      <div class="skeleton h-4 w-4 rounded"></div>
    </div>

    <div class="px-5 py-4 flex justify-between">
      <div class="skeleton h-4 w-16 rounded"></div>
      <div class="skeleton h-4 w-4 rounded"></div>
    </div>

  </div>

</div>


<!-- CONTENT ASLI -->
<div id="content" class="hidden">

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

<div class="max-w-xl mx-auto px-6 py-10">

  <!-- HEADER -->
  <div class="mb-10 text-center">
    <h1 class="text-2xl font-semibold text-[#006FB8]">
      Profil
    </h1>
  </div>

  <!-- ================= VIEW MODE ================= -->
  <div id="viewMode">

    <div class="bg-white border rounded-lg p-6 text-center">

      <!-- AVATAR -->
      <div class="flex justify-center">
        @if(!empty($user->photo))
          <img src="{{ url('uploads/'.$user->photo) }}"
               class="w-24 h-24 rounded-full object-cover border">
        @else
          <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
            👤
          </div>
        @endif
      </div>

      <h2 class="mt-4 text-lg font-semibold">{{ $user->name }}</h2>
      <p class="text-sm text-gray-500">{{ $user->email }}</p>

    </div>

    <!-- MENU -->
    <div class="mt-6 bg-white border rounded-lg divide-y">

      <!-- EDIT -->
      <button onclick="openEdit()"
        class="w-full flex justify-between px-5 py-4 hover:bg-gray-50">
        <span>Edit profil</span>
        <span>›</span>
      </button>

      <!-- LOGOUT -->
      <button onclick="openLogoutModal()"
        class="w-full flex justify-between px-5 py-4 hover:bg-gray-50">
        <span>Keluar</span>
        <span>›</span>
      </button>

    </div>

  </div>


  <!-- ================= EDIT MODE ================= -->
  <div id="editMode" class="hidden">

    <form action="{{ route('user.update.profile') }}" method="POST" enctype="multipart/form-data"
      class="bg-white border rounded-lg p-6">
      @csrf

      <!-- FOTO -->
      <div class="mb-4">
        <label class="text-sm">Foto</label>
        <input type="file" name="photo" class="mt-1 w-full text-sm">
      </div>

      <!-- NAMA -->
      <div class="mb-4">
        <label class="text-sm">Nama</label>
        <input type="text" name="name" value="{{ $user->name }}"
          class="w-full border rounded-md px-3 py-2 mt-1">
      </div>

      <!-- EMAIL -->
      <div class="mb-4">
        <label class="text-sm">Email</label>
        <input type="email" name="email" value="{{ $user->email }}"
          class="w-full border rounded-md px-3 py-2 mt-1">
      </div>

      <!-- PHONE -->
      <div class="mb-4">
        <label class="text-sm">No HP</label>
        <input type="text" name="phone" value="{{ $user->phone }}"
          class="w-full border rounded-md px-3 py-2 mt-1">
      </div>

      <!-- ADDRESS -->
      <div class="mb-4">
        <label class="text-sm">Alamat</label>
        <textarea name="address"
          class="w-full border rounded-md px-3 py-2 mt-1">{{ $user->address }}</textarea>
      </div>

      <!-- BUTTON -->
      <div class="flex justify-end gap-3 mt-6">
        <button type="button" onclick="closeEdit()"
          class="px-4 py-2 border rounded-md">
          Batal
        </button>

        <button
          class="px-4 py-2 bg-[#006FB8] text-white rounded-md">
          Simpan
        </button>
      </div>

    </form>

  </div>

</div>


<!-- MODAL LOGOUT -->
<div id="logoutModal"
  class="fixed inset-0 bg-black/40 hidden items-center justify-center">

  <div class="bg-white rounded-lg w-[90%] max-w-sm p-6">
    <h2 class="text-lg font-semibold mb-2">Keluar</h2>
    <p class="text-sm text-gray-600 mb-6">
      Apakah Anda yakin ingin keluar?
    </p>

    <div class="flex justify-end gap-3">
      <button onclick="closeLogoutModal()"
        class="px-4 py-2 border rounded-md">Batal</button>

      <form action="{{ route('user.logout') }}" method="POST">
        @csrf
        <button class="px-4 py-2 bg-red-500 text-white rounded-md">
          Keluar
        </button>
      </form>
    </div>
  </div>
</div>


<script>
function openEdit(){
  document.getElementById('viewMode').classList.add('hidden')
  document.getElementById('editMode').classList.remove('hidden')
}

function closeEdit(){
  document.getElementById('editMode').classList.add('hidden')
  document.getElementById('viewMode').classList.remove('hidden')
}

function openLogoutModal(){
  document.getElementById('logoutModal').classList.remove('hidden')
  document.getElementById('logoutModal').classList.add('flex')
}

function closeLogoutModal(){
  document.getElementById('logoutModal').classList.add('hidden')
  document.getElementById('logoutModal').classList.remove('flex')
}

/* LOADING SKELETON */
window.onload = () => {
  setTimeout(() => {
    document.getElementById('skeleton').style.display = 'none'
    document.getElementById('content').classList.remove('hidden')
  }, 600)
}
</script>

</body>
</html>

</div>

@endsection