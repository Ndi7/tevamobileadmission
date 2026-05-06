<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'User' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F2F4F7]">

    {{-- HEADER --}}
    @include('user.partials.header', [
        'title' => $title ?? 'Dashboard',
        'mode'  => $mode ?? 'dashboard',

        'showProfile' => ($mode ?? 'dashboard') == 'dashboard',
        'showNotif'   => ($mode ?? 'dashboard') == 'dashboard',
        'headerType'  => ($mode ?? '') == 'info' ? 'info' : null,
    ])

    {{-- NAVBAR --}}
    @include('user.partials.navbar')

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

<script>
let notifLoaded = false

function openNotif(){
    document.getElementById('notifPanel').classList.remove('translate-x-full')
    document.getElementById('notifOverlay').classList.remove('hidden')

    if(!notifLoaded){
        loadNotif()
        notifLoaded = true
    }
}

function closeNotif(){
    document.getElementById('notifPanel').classList.add('translate-x-full')
    document.getElementById('notifOverlay').classList.add('hidden')
}

// ✅ UPDATE BADGE
function updateBadge(count){
    let badge = document.getElementById('notifBadge')

    if(!badge) return

    if(count > 0){
        badge.innerText = count
        badge.classList.remove('hidden')
    }else{
        badge.classList.add('hidden')
    }
}

// ✅ MARK AS READ
async function markAsRead(id){
    let fd = new FormData()
    fd.append('id', id)

    await fetch('/api/notifikasi/read', {
        method:'POST',
        body: fd
    })
}

// ✅ LOAD NOTIF
async function loadNotif(){
    let container = document.getElementById('notifContent')
    let loading = document.getElementById('notifLoading')

    if(!container || !loading) return

    try{
        let fd = new FormData()
        fd.append('user_id', "{{ auth()->id() }}")

        let res = await fetch('/api/notifikasi/user', {
            method:'POST',
            body: fd
        })

        let data = await res.json()

        loading.remove()

        if(!data.data || !data.data.length){
            container.innerHTML = '<p class="text-center text-sm text-gray-500">Tidak ada notifikasi</p>'
            updateBadge(0)
            return
        }

        // 🔥 HITUNG UNREAD
        let unread = data.data.filter(n => n.is_read == 0).length
        updateBadge(unread)

        data.data.forEach(n => {

            let isRead = n.is_read == 1

            let item = document.createElement('div')
            item.className = `p-4 rounded-lg border cursor-pointer
                ${isRead ? 'border-gray-200' : 'border-[#006FB8]'}`

            item.innerHTML = `
                <p class="font-semibold text-sm">${n.judul ?? '-'}</p>
                <p class="text-xs text-gray-500 mt-1">${n.pesan ?? '-'}</p>
            `

            item.onclick = async () => {

                item.classList.remove('border-[#006FB8]')
                item.classList.add('border-gray-200')

                await markAsRead(n.id)

                // 🔥 UPDATE BADGE SAAT KLIK
                let badge = document.getElementById('notifBadge')
                let current = parseInt(badge?.innerText || 0)

                if(current > 1){
                    badge.innerText = current - 1
                }else{
                    badge.classList.add('hidden')
                }
            }

            container.appendChild(item)
        })

    }catch(e){
        console.error(e)
        loading.innerText = "Gagal load notifikasi"
    }
}

// ✅ AUTO LOAD SAAT HALAMAN DIBUKA
window.addEventListener('load', () => {
    loadNotif()
})
</script>

</body>
</html>