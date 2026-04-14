<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SMK Lentera Bangsa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>

<body class="h-full overflow-hidden bg-surface-900 font-sans antialiased">

    {{-- ═══════════════════════════════════════════
         Background: Efek cahaya lentera
         ═══════════════════════════════════════════ --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        {{-- Glow utama — cahaya lentera di tengah atas --}}
        <div
            class="absolute -top-32 left-1/2 -translate-x-1/2 h-[500px] w-[700px] rounded-full bg-brand-500/15 blur-[120px] animate-glow-pulse">
        </div>

        {{-- Glow sekunder — aksen emas --}}
        <div class="absolute top-1/3 left-1/4 h-[300px] w-[300px] rounded-full bg-accent-500/8 blur-[100px] animate-glow-pulse"
            style="animation-delay: 1.5s;"></div>

        {{-- Glow tersier --}}
        <div class="absolute bottom-0 right-1/4 h-[250px] w-[250px] rounded-full bg-brand-400/6 blur-[80px] animate-glow-pulse"
            style="animation-delay: 3s;"></div>

        {{-- Grid pattern subtle --}}
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M0 0h40v40H0z&quot; fill=&quot;none&quot; stroke=&quot;%23fff&quot; stroke-width=&quot;0.5&quot;/%3E%3C/svg%3E');">
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         Konten Login
         ═══════════════════════════════════════════ --}}
    <div class="relative z-10 flex h-full items-center justify-center px-4">
        <div class="w-full max-w-md animate-fade-in">

            {{-- Brand / Logo Area --}}
            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-600/20 ring-1 ring-brand-500/30">
                    <x-icon name="lentera" class="w-8 h-8 text-brand-400" />
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight sm:text-3xl">
                    SMK Lentera Bangsa
                </h1>
                <p class="mt-1.5 text-sm text-gray-500 font-light">
                    Sistem Absensi Digital
                </p>
            </div>

            {{-- Login Card — Glass Morphism --}}
            <div class="glass rounded-2xl p-6 sm:p-8">
                <h2 class="mb-1 text-lg font-semibold text-white">Masuk ke Akun</h2>
                <p class="mb-6 text-sm text-gray-500">Masukkan username dan password untuk melanjutkan.</p>

                {{-- Error Message --}}
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

                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">

                    @csrf

                    {{-- Field Username --}}
                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-medium text-gray-300">Username</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <x-icon name="user" class="w-4 h-4 text-gray-500" />
                            </div>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required
                                autofocus autocomplete="username" placeholder="NIS / NIP / Username"
                                class="block w-full rounded-lg border border-white/10 bg-white/5 py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-600 transition-all duration-200 focus:border-brand-500/50 focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>

                    {{-- Field Password --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-300">Password</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <input x-bind:type="showPassword ? 'text' : 'password'" id="password" name="password"
                                required autocomplete="current-password" placeholder="Masukkan password"
                                class="block w-full rounded-lg border border-white/10 bg-white/5 py-2.5 pl-10 pr-11 text-sm text-white placeholder-gray-600 transition-all duration-200 focus:border-brand-500/50 focus:bg-white/8 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <button type="button" x-on:click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-500 hover:text-gray-300 transition-colors"
                                :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                {{-- Eye icon — buka/tutup --}}
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-white/20 bg-white/5 text-brand-600 focus:ring-brand-500/30 focus:ring-offset-0">
                            <span class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Ingat
                                saya</span>
                        </label>
                    </div>

                    {{-- Tombol Login --}}
                    <button type="submit"
                        class="group relative w-full overflow-hidden rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all duration-300 hover:bg-brand-500 hover:shadow-brand-500/30 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:ring-offset-2 focus:ring-offset-surface-900 active:scale-[0.98]">
                        {{-- Shimmer effect pada hover --}}
                        <span
                            class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
                        <span class="relative">Masuk</span>
                    </button>
                </form>
            </div>

            {{-- Info bawah --}}
            <p class="mt-6 text-center text-xs text-gray-600">
                Khusus untuk administrator, scanner, dan siswa SMK Lentera Bangsa.
            </p>
        </div>
    </div>

    {{-- Style untuk x-cloak --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</body>

</html>
