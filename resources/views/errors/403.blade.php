<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak — SMK Lentera Bangsa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body class="flex h-screen items-center justify-center bg-surface-900 font-sans antialiased">
    <div class="text-center animate-fade-in">
        <p class="text-8xl font-black text-brand-600/30">403</p>
        <h1 class="mt-4 text-xl font-bold text-white">Akses Ditolak</h1>
        <p class="mt-2 text-sm text-gray-500">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ route('login') }}"
            class="mt-6 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-all hover:bg-brand-500">
            Kembali ke Login
        </a>
    </div>
</body>

</html>
