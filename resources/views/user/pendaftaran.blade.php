<!DOCTYPE html>
<html lang="id">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Pendaftaran</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
:root{
  --primary:#006FB8;
  --danger:#DC2626;
}

.section-title{
  font-size:13px;
  font-weight:600;
  color:#6B7280;
  margin-bottom:10px;
  text-transform:uppercase;
  letter-spacing:.5px;
}

.label{
  font-size:13px;
  color:#374151;
  margin-bottom:4px;
  display:block;
}

.required::after{
  content:" *";
  color:var(--danger);
}

.input{
  width:100%;
  padding:12px;
  border-radius:6px;
  border:1px solid #E5E7EB;
  background:#fff;
  font-size:14px;
  transition:.2s;
}

.input:focus{
  outline:none;
  border-color:var(--primary);
  box-shadow:0 0 0 1px var(--primary);
}

.input.error{
  border-color:var(--danger);
}

.btn-primary{
  background:var(--primary);
}

.btn-primary:hover{
  background:#005A98;
}
</style>
</head>

<body class="bg-gray-50">

<div class="max-w-6xl mx-auto px-6 py-10">

  <!-- HEADER -->
  <div class="mb-10 border-b pb-4">
    <h1 class="text-2xl font-semibold text-[#006FB8]">
      Formulir Pendaftaran
    </h1>
    <p class="text-gray-500 text-sm mt-1">
      Lengkapi data berikut untuk melanjutkan pendaftaran ( * wajib )
    </p>
  </div>

  <!-- GRID -->
  <div class="grid md:grid-cols-3 gap-12">

    <!-- LEFT -->
    <div class="md:col-span-2 space-y-10">

      <!-- DATA SISWA -->
      <div>
        <h2 class="section-title">Data Siswa</h2>

        <div class="grid md:grid-cols-2 gap-4">

          <div>
            <label class="label required">Nama lengkap</label>
            <input id="nama" class="input">
          </div>

          <div>
            <label class="label required">Agama</label>
            <input id="agama" class="input">
          </div>

          <div>
            <label class="label required">HP Orangtua</label>
            <input id="hpOrtu" class="input">
          </div>

          <div>
            <label class="label required">HP Siswa</label>
            <input id="hpSiswa" class="input">
          </div>

          <div>
            <label class="label required">Tanggal Lahir</label>
            <input type="date" id="tgl" class="input">
          </div>

          <div>
            <label class="label required">Nama Sekolah</label>
            <input id="sekolah" class="input">
          </div>

        </div>
      </div>

      <!-- KELAS -->
      <div>
        <h2 class="section-title">Kelas & Program</h2>

        <label class="label required">Pilih Kelas</label>
        <select id="kelas" class="input w-full mb-3">
          <option value="">Pilih Kelas</option>
        </select>

        <div id="mapel" class="space-y-2"></div>
      </div>

      <!-- ALAMAT -->
      <div>
        <h2 class="section-title">Alamat</h2>

        <label class="label required">Alamat Lengkap</label>
        <textarea id="alamat" rows="3" class="input"></textarea>
      </div>

    </div>

    <!-- RIGHT -->
    <div class="space-y-10">

      <!-- KELUARGA -->
      <div>
        <h2 class="section-title">Data Keluarga</h2>

        <div class="space-y-4">

          <div class="grid grid-cols-3 gap-3">
            <input id="ayah" placeholder="Nama Ayah" class="input col-span-2">
            <input id="umurAyah" type="number" placeholder="Umur" class="input">
          </div>

          <div class="grid grid-cols-3 gap-3">
            <input id="ibu" placeholder="Nama Ibu" class="input col-span-2">
            <input id="umurIbu" type="number" placeholder="Umur" class="input">
          </div>

          <div class="grid grid-cols-3 gap-3">
            <input id="saudara" placeholder="Saudara Kandung" class="input col-span-2">
            <input id="umurSaudara" type="number" placeholder="Umur" class="input">
          </div>

          <div class="grid grid-cols-3 gap-3">
            <input id="lainnya" placeholder="Anggota lain (opsional)" class="input col-span-2">
            <input id="umurLainnya" type="number" placeholder="Umur" class="input">
          </div>

        </div>
      </div>

      <!-- TOTAL -->
      <div>
        <h2 class="section-title">Total Biaya</h2>

        <div class="text-2xl font-semibold text-[#006FB8]" id="total">
          Rp 0
        </div>
      </div>

      <!-- FOTO -->
      <div>
        <h2 class="section-title">Upload Foto</h2>

        <label class="label required">Foto Siswa</label>
        <input type="file" id="foto" class="input">

        <img id="previewFoto"
          class="mt-4 w-full h-48 object-cover rounded hidden border">
      </div>

      <!-- BUTTON -->
      <button onclick="submitForm()"
        class="w-full btn-primary text-white py-3 rounded text-sm font-medium">
        Kirim Pendaftaran
      </button>

    </div>

  </div>

</div>

<script>
let total = 0
let mapelData = []

// =======================
// STATE (SAMA DENGAN FLUTTER)
// =======================
let selectedMapel = {
  wajib: [],
  reguler: [],
  ekskul: []
}

// =======================
// ELEMENT (FIX ERROR)
// =======================
const foto = document.getElementById('foto')
const kelasEl = document.getElementById('kelas')

// =======================
// VALIDASI (FIX FOTO ERROR)
// =======================
function validate(){

  let requiredFields = [
    'nama','agama','hpOrtu','hpSiswa',
    'tgl','sekolah','kelas','alamat'
  ]

  let firstError = null
  let valid = true

  requiredFields.forEach(id=>{
    let el = document.getElementById(id)

    if(!el.value){
      el.classList.add('error')
      valid = false
      if(!firstError) firstError = el
    }else{
      el.classList.remove('error')
    }
  })

  if(!foto.files || !foto.files[0]){
    alert("Foto wajib diupload")
    return false
  }

  if(!valid){
    alert("Mohon lengkapi semua data wajib")
    firstError.focus()
    return false
  }

  return true
}

// =======================
// PREVIEW FOTO
// =======================
foto.addEventListener('change', function(){
  const file = this.files[0]
  if(!file) return

  previewFoto.src = URL.createObjectURL(file)
  previewFoto.classList.remove('hidden')
})

// =======================
// FETCH KELAS
// =======================
async function loadKelas(){
  let res = await fetch('/api/kelas')
  let result = await res.json()

  let data = result.data || result

  data.forEach(k=>{
    let opt = document.createElement('option')

    // 🔥 simpan full object biar sama kayak Flutter
    opt.value = JSON.stringify(k)
    opt.text = k.nama_kelas

    kelasEl.appendChild(opt)
  })
}

// =======================
// FETCH MAPEL
// =======================
async function loadMapel(id){

  total = 0
  selectedMapel = { wajib: [], reguler: [], ekskul: [] }

  document.getElementById('total').innerText = "Rp 0"

  let res = await fetch('/api/kelas/' + id + '/mapel')
  let data = await res.json()

  mapelData = [
    ...(data.wajib || []).map(m => ({
      nama: m.mapel?.nama_mapel,
      harga: m.harga,
      tipe: 'wajib'
    })),
    ...(data.reguler || []).map(m => ({
      nama: m.mapel?.nama_mapel,
      harga: m.harga,
      tipe: 'reguler'
    })),
    ...(data.ekskul || []).map(m => ({
      nama: m.mapel?.nama_mapel,
      harga: m.harga,
      tipe: 'ekskul'
    })),
  ]

  renderMapel()
}

// =======================
// RENDER MAPEL
// =======================
function renderMapel(){
  mapel.innerHTML = ''

  const groups = { wajib: [], reguler: [], ekskul: [] }

  mapelData.forEach(m => {
    groups[m.tipe].push(m)
  })

  Object.keys(groups).forEach(tipe => {

    if(groups[tipe].length === 0) return

    let title = document.createElement('div')
    title.className = "font-semibold text-sm mt-3 mb-1"
    title.innerText = tipe.toUpperCase()
    mapel.appendChild(title)

    groups[tipe].forEach(m => {

      let div = document.createElement('div')
      div.className = "flex justify-between border-b py-2 text-sm"

      if(tipe === 'wajib'){

        selectedMapel.wajib.push(m)
        total += m.harga

        div.innerHTML = `
          <label class="flex gap-2">
            <input type="checkbox" checked disabled>
            ${m.nama}
          </label>
          <span>Rp ${m.harga}</span>
        `

      } else {

        div.innerHTML = `
          <label class="flex gap-2">
            <input type="checkbox"
              onchange='toggleMapel(${JSON.stringify(m)}, this, "${tipe}")'>
            ${m.nama}
          </label>
          <span>Rp ${m.harga}</span>
        `
      }

      mapel.appendChild(div)
    })
  })

  document.getElementById('total').innerText = "Rp " + total
}

// =======================
// TOGGLE MAPEL
// =======================
function toggleMapel(item, el, tipe){

  if(el.checked){
    total += item.harga
    selectedMapel[tipe].push(item)
  }else{
    total -= item.harga
    selectedMapel[tipe] =
      selectedMapel[tipe].filter(x => x.nama !== item.nama)
  }

  document.getElementById('total').innerText = "Rp " + total
}

// =======================
// EVENT KELAS (FIX ERROR)
// =======================
kelasEl.addEventListener('change', e=>{

  if(!e.target.value) return

  let selected = JSON.parse(e.target.value)
  loadMapel(selected.id)
})

// =======================
// SUBMIT (FIX TOTAL ERROR)
// =======================
async function submitForm(){

  console.log("SUBMIT JALAN")

  if(!validate()) return

  if(!kelasEl.value){
    alert("Pilih kelas dulu")
    return
  }

  let selectedKelas = JSON.parse(kelasEl.value)

  let formData = new FormData()

  formData.append('nama', nama.value)
  formData.append('hp_orangtua', hpOrtu.value)
  formData.append('hp_siswa', hpSiswa.value)
  formData.append('agama', agama.value)
  formData.append('tanggal_lahir', tgl.value)
  formData.append('sekolah', sekolah.value)
  formData.append('kelas', selectedKelas.nama_kelas)
  formData.append('alamat', alamat.value)

  formData.append('mapel_wajib', JSON.stringify(selectedMapel.wajib))
  formData.append('mapel_reguler', JSON.stringify(selectedMapel.reguler))
  formData.append('mapel_ekskul', JSON.stringify(selectedMapel.ekskul))

  formData.append('total_harga', total.toString())

  formData.append('nama_ayah', ayah.value)
  formData.append('nama_ibu', ibu.value)

  if(foto.files[0]){
    formData.append('foto', foto.files[0])
  }

  try{

    // 🔥 AMBIL TOKEN (INI HARUS DI LUAR FETCH)
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

    let res = await fetch('/pendaftaran', {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
      headers: {
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })

    let result = await res.json()

    console.log("RESPONSE:", result)

    if(res.ok){
      window.location.href = "/success"
    }else{
      alert(result.message || "Gagal kirim")
    }

  }catch(e){
    console.error("ERROR:", e)
    alert("Terjadi error saat kirim data")
  }
}

// =======================
loadKelas()
</script>

</body>
</html>