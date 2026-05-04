<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login Berhasil</title>
</head>
<body>
    <h2>Login Berhasil</h2>

    <p>Halo <b>{{ $user->name }}</b>,</p>

    <p>Akun admin kamu berhasil login.</p>

    <ul>
        <li><b>Email:</b> {{ $user->email }}</li>
        <li><b>Waktu:</b> {{ $time }}</li>
        <li><b>IP Address:</b> {{ $ip }}</li>
    </ul>

    <p>Jika ini bukan kamu, segera ganti password.</p>

    <br>
    <small>Teva Admin System</small>
</body>
</html>