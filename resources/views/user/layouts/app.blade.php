<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'User' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F2F4F7]">

    {{-- HEADER (PUTIH + BIRU) --}}
    @include('user.partials.header', [
    'title' => $title ?? 'Dashboard',
    'mode' => $mode ?? 'dashboard'
])

    {{-- NAVBAR --}}
    @include('user.partials.navbar')

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

</body>
</html>