<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun | Bimbel</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#006FB8',
                        primaryDark: '#005A94'
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-white flex items-center justify-center px-4">

    <!-- CONTAINER UTAMA -->
    <div class="w-full max-w-md">

        <!-- Judul -->
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">
            Daftar Akun
        </h1>
        <p class="text-center text-gray-500 mb-10">
            Pendaftaran siswa bimbingan belajar berbasis web
        </p>

        @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded-xl">
        {{ $errors->first() }}
    </div>
@endif

@if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded-xl">
        {{ session('success') }}
    </div>
@endif

        <!-- FORM -->
        <form action="{{ route('user.register.process') }}" method="POST">
    @csrf

            <!-- Nama Pengguna -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Pengguna
                </label>
                <input type="text" name="username" required
                    class="w-full h-12 px-4 text-sm rounded-xl border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email" name="email" required
                    class="w-full h-12 px-4 text-sm rounded-xl border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <!-- No Telepon -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    No Telepon
                </label>
                <input type="text" name="notelpon" required
                    class="w-full h-12 px-4 text-sm rounded-xl border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kata Sandi
                </label>
                <input type="password" name="password" required
                    class="w-full h-12 px-4 text-sm rounded-xl border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Konfirmasi Kata Sandi
                </label>
                <input type="password" name="password_confirmation" required
                    class="w-full h-12 px-4 text-sm rounded-xl border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full h-12 bg-primary hover:bg-primaryDark
                       text-white font-semibold rounded-xl transition">
                Daftar
            </button>
        </form>

        <!-- LOGIN LINK -->
        <div class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun?
            <a href="/user/login" class="text-primary font-medium hover:underline">
                Login
            </a>
        </div>

    </div>

</body>
</html>