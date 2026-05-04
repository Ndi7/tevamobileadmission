<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Homepage | Bimbel</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Config -->
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
<body class="min-h-screen bg-gradient-to-br from-primary to-primaryDark flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-xl p-10 w-full max-w-md text-center">
        
        <!-- Judul -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Selamat Datang di Bimbel BUC TEVA CENTRE
        
        </h1>

        <!-- Deskripsi -->
        <p class="text-gray-500 mb-8">
            Sistem pendaftaran siswa bimbingan belajar berbasis web untuk proses yang cepat, mudah, dan terintegrasi.
        </p>

        <!-- Tombol -->
        <div class="space-y-4">
            <a href="/register"
               class="block w-full bg-primary hover:bg-primaryDark text-white font-semibold py-3 rounded-lg transition duration-200">
                Daftar
            </a>

            <a href="/user/login"
               class="block w-full border border-primary text-primary hover:bg-blue-50 font-semibold py-3 rounded-lg transition duration-200">
                Login
            </a>
        </div>

        <!-- Login Admin -->
        <div class="mt-8">
            <a href="/admin/login"
               class="text-sm text-gray-400 hover:text-primary transition">
                Login Admin
            </a>
        </div>

    </div>

</body>
</html>