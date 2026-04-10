@extends('layouts.auth')

@section('content')

<script>
window.history.forward();
function noBack() {
    window.history.forward();
}
</script>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
    
<div class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <img src="/assets/images/logo.png" alt="Logo Bimbel" class="w-16 h-16 mb-2">
            <h1 class="text-2xl font-bold text-primary">BIMBEL TEMA</h1>
            <p class="text-gray-500 text-sm mt-1">Masuk ke akun admin Anda</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-gray-600 text-sm mb-1">Email</label>
                <input type="email" id="email" name="email"
                       class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:outline-none"
                       placeholder="admin@bimbel.com" required autofocus>
            </div>

            <div>
                <label for="password" class="block text-gray-600 text-sm mb-1">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full p-3 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:outline-none"
                       placeholder="********" required>
            </div>

            <button type="submit"
                    class="w-full bg-primary text-white p-3 rounded-lg font-semibold hover:bg-primary/90 transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            © {{ date('Y') }} Bimbel TEMA. All rights reserved.
        </p>
    </div>

</div>
@endsection
