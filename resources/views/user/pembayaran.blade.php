<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran</title>
<script src="https://cdn.tailwindcss.com"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50 text-gray-800">

<div class="max-w-md mx-auto px-6 py-10">

  <!-- HEADER -->
  <div class="mb-8 border-b pb-4">
    <h1 class="text-xl font-semibold text-[#006FB8]">
      Pembayaran
    </h1>
    <p class="text-sm text-gray-500 mt-1">
      Selesaikan pembayaran untuk melanjutkan
    </p>
  </div>

  <!-- STEP -->
  <div class="flex justify-between text-xs text-gray-500 mb-6">
    <span>Metode</span>
    <span>Bayar</span>
    <span>Upload</span>
  </div>

  <!-- CARD -->
  <div class="bg-white border border-gray-200 rounded-lg">

    <div class="p-5">

      <!-- STEP 1 -->
      <div id="step1">
        <p class="text-sm font-medium mb-3">Pilih metode</p>

        <div onclick="setMetode('bank')" class="item" id="bank">
          Transfer Bank
        </div>

        <div onclick="setMetode('qris')" class="item" id="qris">
          QRIS
        </div>
      </div>

      <!-- STEP 2 -->
      <div id="step2" class="hidden text-sm">

        <div id="bankContent">
          <p class="font-medium mb-3">Transfer Bank</p>

          <div class="space-y-2 text-gray-600">
            <p>{{ $setting->bank }}</p>

            <div class="flex justify-between">
              <span>{{ $setting->rekening }}</span>
              <button onclick="copyRek()" class="text-[#006FB8] text-sm">
                Salin
              </button>
            </div>

            <p>a.n {{ $setting->pemilik }}</p>
          </div>
        </div>

        <div id="qrisContent" class="hidden text-center">
          <img src="{{ asset('storage/'.$setting->qris) }}" class="mx-auto w-40 mb-2">
          <p class="text-xs text-gray-500">Scan QR</p>
        </div>

      </div>

      <!-- STEP 3 -->
      <div id="step3" class="hidden">
        <p class="text-sm font-medium mb-3">Upload bukti</p>

        <input type="file" id="bukti"
          class="border border-gray-200 rounded-md p-2 w-full mb-3 text-sm"
          onchange="previewImg()">

        <img id="preview" class="hidden w-full rounded-md border">
      </div>

    </div>

    <!-- FOOTER -->
    <div class="border-t bg-gray-50 px-5 py-4">

      <div class="flex justify-between text-sm mb-3">
        <span class="text-gray-500">Total</span>
        <span class="font-medium">
          Rp {{ number_format($jumlah) }}
        </span>
      </div>

      <button id="btn"
        onclick="nextStep()"
        disabled
        class="w-full bg-[#006FB8] text-white py-2.5 rounded-md text-sm disabled:bg-gray-300 flex justify-center items-center gap-2">

        <span id="btnText">Lanjut</span>

        <svg id="loading" class="w-4 h-4 animate-spin hidden"
          xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
        </svg>

      </button>

    </div>

  </div>

</div>

<!-- TOAST -->
<div id="toast"
  class="fixed bottom-5 right-5 bg-black text-white text-sm px-4 py-2 rounded opacity-0 translate-y-5 transition">
</div>

<style>
.item{
  border:1px solid #e5e7eb;
  padding:10px;
  border-radius:6px;
  margin-bottom:8px;
  cursor:pointer;
}
.active{
  border-color:#006FB8;
  background:#f0f7fc;
}
</style>

<script>
let step = 1
let metode = null

function toast(msg){
  let t = document.getElementById('toast')
  t.innerText = msg
  t.classList.remove('opacity-0','translate-y-5')
  t.classList.add('opacity-100','translate-y-0')

  setTimeout(()=>{
    t.classList.add('opacity-0','translate-y-5')
  },2000)
}

function loadingState(state){
  btn.disabled = state
  document.getElementById('loading').classList.toggle('hidden', !state)
  btnText.innerText = state ? 'Memproses...' : 'Lanjut'
}

function updateBtn(){
  if(step==1) btn.disabled = !metode
  if(step==2) btn.disabled = false
  if(step==3) btn.disabled = !bukti.files.length
}

function setMetode(val){
  metode = val
  document.querySelectorAll('.item').forEach(el=>el.classList.remove('active'))
  document.getElementById(val).classList.add('active')
  updateBtn()
}

function nextStep(){

  if(step==1){
    if(!metode) return toast("Pilih metode dulu")

    step1.classList.add('hidden')
    step2.classList.remove('hidden')

    if(metode=='bank'){
      bankContent.classList.remove('hidden')
      qrisContent.classList.add('hidden')
    }else{
      qrisContent.classList.remove('hidden')
      bankContent.classList.add('hidden')
    }

    step=2
    updateBtn()
    return
  }

  if(step==2){
    step2.classList.add('hidden')
    step3.classList.remove('hidden')

    step=3
    updateBtn()
    return
  }

  if(step==3){
    submit()
  }
}

function previewImg(){
  let file = bukti.files[0]
  if(!file) return
  preview.src = URL.createObjectURL(file)
  preview.classList.remove('hidden')
  updateBtn()
}

function copyRek(){
  navigator.clipboard.writeText("{{ $setting->rekening }}")
  toast("Rekening disalin")
}

async function submit(){

  let file = bukti.files[0]
  if(!file) return toast("Upload bukti dulu")

  loadingState(true)

  let fd = new FormData()
  fd.append('bukti', file)
  fd.append('pendaftar_id', "{{ $pendaftar->id }}")
  fd.append('jumlah', "{{ $jumlah }}") // ✅ FIX DI SINI

  try{
    let res = await fetch('/pembayaran',{
      method:'POST',
      body:fd,
      headers:{
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })

    if(res.ok){
      toast("Berhasil kirim")
      setTimeout(()=>location.href="/user/dashboard",1000)
    }else{
      toast("Gagal kirim")
    }

  }catch{
    toast("Error jaringan")
  }

  loadingState(false)
}

updateBtn()
</script>

</body>
</html>