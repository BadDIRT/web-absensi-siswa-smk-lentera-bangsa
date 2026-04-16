<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — SMK Lentera Bangsa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-surface-900 font-sans antialiased">

    {{-- Background --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div
            class="absolute -top-32 left-1/2 -translate-x-1/2 h-[500px] w-[700px] rounded-full bg-brand-500/15 blur-[120px] animate-glow-pulse">
        </div>
        <div class="absolute top-1/3 left-1/4 h-[300px] w-[300px] rounded-full bg-accent-500/8 blur-[100px] animate-glow-pulse"
            style="animation-delay: 1.5s;"></div>
        <div class="absolute bottom-0 right-1/4 h-[250px] w-[250px] rounded-full bg-brand-400/6 blur-[80px] animate-glow-pulse"
            style="animation-delay: 3s;"></div>
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M0 0h40v40H0z&quot; fill=&quot;none&quot; stroke=&quot;%23fff&quot; stroke-width=&quot;0.5&quot;/%3E%3C/svg%3E');">
        </div>
    </div>

    {{-- Konten --}}
    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-8 sm:py-0">
        <div class="w-full max-w-md animate-fade-in my-auto mt-10">

            {{-- Logo --}}
            <div class="mb-8 text-center">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo SMK Lentera Bangsa"
                    class="mx-auto mb-4 h-16 w-16 rounded-2xl object-cover ring-1 ring-white/10 sm:h-20 sm:w-20 bg-white">
                <h1 class="text-2xl font-bold text-white tracking-tight sm:text-3xl">
                    SMK Lentera Bangsa
                </h1>
                <p class="mt-1.5 text-sm text-gray-500 font-light">
                    Buat Akun Siswa Baru
                </p>
            </div>

            {{-- Card --}}
            <div class="glass rounded-2xl p-5 sm:p-8">
                <h2 class="mb-1 text-lg font-semibold text-white">Daftar Akun</h2>
                <p class="mb-5 sm:mb-6 text-sm text-gray-500">Masukkan NIPD untuk verifikasi data siswa, lalu buat
                    username dan password.</p>

                {{-- Error --}}
                @if ($errors->any())
                    <div
                        class="mb-5 flex items-start gap-3 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-400">
                        <svg class="mt-0.5 w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5"
                    x-data="{ showPassword: false }">
                    @csrf

                    {{-- Info --}}
                    <div
                        class="flex items-start gap-3 rounded-lg border border-brand-500/20 bg-brand-500/5 px-3.5 py-3 sm:px-4 sm:py-3">
                        <svg class="mt-0.5 w-4 h-4 shrink-0 text-brand-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <p class="text-xs text-brand-300/70 leading-relaxed">
                            Pastikan NIPD kamu sudah terdaftar di data sekolah. Jika belum, hubungi administrator.
                        </p>
                    </div>

                    {{-- NIPD --}}
                    <div>
                        <label for="nipd" class="mb-1.5 block text-sm font-medium text-gray-300">
                            NIPD <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 4.338 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="nipd" name="nipd" value="{{ old('nipd') }}" required
                                autofocus autocomplete="off" placeholder="Contoh: 242510181"
                                class="block w-full rounded-lg border border-white/10 bg-white/5 py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-600 transition-all duration-200 focus:border-brand-500/50 focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-medium text-gray-300">
                            Username <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required
                                autocomplete="username" placeholder="Buat username"
                                class="block w-full rounded-lg border border-white/10 bg-white/5 py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-600 transition-all duration-200 focus:border-brand-500/50 focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-300">
                            Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <input x-bind:type="showPassword ? 'text' : 'password'" id="password" name="password"
                                required autocomplete="new-password" placeholder="Minimal 6 karakter"
                                class="block w-full rounded-lg border border-white/10 bg-white/5 py-2.5 pl-10 pr-11 text-sm text-white placeholder-gray-600 transition-all duration-200 focus:border-brand-500/50 focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <button type="button" x-on:click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-500 hover:text-gray-300 transition-colors">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-300">
                            Konfirmasi Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                autocomplete="new-password" placeholder="Ulangi password"
                                class="block w-full rounded-lg border border-white/10 bg-white/5 py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-600 transition-all duration-200 focus:border-brand-500/50 focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <button type="submit"
                        class="group relative w-full overflow-hidden rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all duration-300 hover:bg-brand-500 hover:shadow-brand-500/30 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:ring-offset-2 focus:ring-offset-surface-900 active:scale-[0.98]">
                        <span
                            class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
                        <span class="relative">Daftar</span>
                    </button>
                </form>
            </div>

            {{-- Link --}}
            <p class="mt-6 text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="font-medium text-brand-400 hover:text-brand-300 transition-colors">Masuk di sini</a>
            </p>
            <p class="mt-2 text-center text-xs text-gray-600">
                Registrasi hanya untuk siswa yang NIPD-nya sudah terdaftar di sistem.
            </p>

        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</body>

</html>
