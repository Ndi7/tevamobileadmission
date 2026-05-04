<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pendaftaran Berhasil</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
:root{
  --primary:#006FB8;
  --success:#6BAE4F;
}
</style>
</head>

<body class="bg-[#F5F5F5]">

<div class="min-h-screen flex items-center justify-center px-6">

  <div class="text-center max-w-md w-full">

    <!-- ICON -->
    <div class="mx-auto w-[90px] h-[90px] rounded-full flex items-center justify-center"
         style="background-color: var(--success)">
      <svg xmlns="http://www.w3.org/2000/svg"
           class="w-12 h-12 text-white"
           fill="none"
           viewBox="0 0 24 24"
           stroke="currentColor"
           stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M5 13l4 4L19 7"/>
      </svg>
    </div>

    <!-- TITLE -->
    <h1 class="mt-6 text-2xl font-bold">
      Pendaftaran Berhasil
    </h1>

    <!-- DESKRIPSI -->
    <p class="mt-4 text-[15px] leading-relaxed text-gray-800">
      Mohon tunggu konfirmasi dari admin kami.<br>
      Jika pendaftaran diterima,<br>
      silakan lanjutkan ke proses pembayaran.
    </p>

    <!-- BUTTON -->
    <a href="/user/dashboard"
       class="block mt-10 w-full h-[50px] leading-[50px] rounded-full text-white text-[16px] font-medium"
       style="background-color: var(--primary)">
      Kembali ke Halaman Utama
    </a>

  </div>

</div>

</body>
</html>