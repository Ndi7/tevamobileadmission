<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ketentuan Pendaftaran</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

<!-- CONTAINER -->
<div class="max-w-4xl mx-auto px-6 py-10">

  <!-- HEADER -->
  <div class="mb-10 border-b pb-4">
    <h1 class="text-2xl font-semibold text-[#006FB8]">
      Ketentuan Pendaftaran
    </h1>
    <p class="text-sm text-gray-500 mt-1">
      Silakan baca dengan seksama sebelum melanjutkan ke proses pendaftaran.
    </p>
  </div>

  <!-- CONTENT -->
  <div class="bg-white border border-gray-200 rounded-lg">

    <!-- SCROLL AREA -->
    <div id="markdown"
      class="prose prose-sm max-w-none p-6 max-h-[420px] overflow-y-auto leading-relaxed">
      <p class="text-gray-400">Memuat ketentuan...</p>
    </div>

    <!-- FOOTER ACTION -->
    <div class="flex items-center justify-between px-6 py-4 border-t bg-gray-50">
      
      <span class="text-xs text-gray-500">
        Dengan melanjutkan, Anda menyetujui semua ketentuan di atas.
      </span>

      <button onclick="goForm()"
        class="bg-[#006FB8] hover:bg-[#005a96] text-white px-6 py-2 rounded-md text-sm transition">
        Setuju & Lanjutkan
      </button>

    </div>

  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<script>
async function loadMarkdown() {
  try {
    const res = await fetch('/ketentuan.md')

    if (!res.ok) throw new Error("Gagal load")

    const text = await res.text()
    document.getElementById('markdown').innerHTML = marked.parse(text)

  } catch (e) {
    document.getElementById('markdown').innerHTML =
      "<p class='text-red-500'>Gagal memuat ketentuan</p>"
  }
}

function goForm() {
  window.location.href = "/user/pendaftaran"
}

loadMarkdown()
</script>

</body>
</html>