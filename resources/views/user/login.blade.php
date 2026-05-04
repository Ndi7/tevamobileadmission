<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-white">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">

    <div class="flex flex-col items-center mb-6">
        <img src="/assets/images/logo.png" class="w-16 h-16 mb-2">
        <h1 class="text-2xl font-bold text-[#006FB8]">BIMBEL TEVA</h1>
        <p class="text-gray-500 text-sm mt-1 text-center">
            Pendaftaran siswa bimbingan belajar berbasis web
        </p>
    </div>

    <!-- ERROR -->
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('user.login.process') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-600 text-sm mb-1">Email</label>
            <input type="email" name="email" required
                class="w-full p-3 border border-gray-300 rounded
                       focus:ring-2 focus:ring-[#006FB8] focus:outline-none">
        </div>

        <div>
            <label class="block text-gray-600 text-sm mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full p-3 border border-gray-300 rounded
                       focus:ring-2 focus:ring-[#006FB8] focus:outline-none">
        </div>

        <button class="w-full bg-[#006FB8] text-white p-3 rounded-lg font-semibold">
            Masuk
        </button>
    </form>

</div>

</body>
</html>