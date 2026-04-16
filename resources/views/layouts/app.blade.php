<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Absensi Siswa SMK Lentera Bangsa">

    <title>@yield('title', 'Absensi — SMK Lentera Bangsa')</title>
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/logo.png') }}">


    {{-- Font: Outfit dari Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- TailwindCSS via Vite --}}
    @vite(['resources/css/app.css'])

    {{-- Alpine.js via CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('head')
</head>

<body class="h-full bg-surface-50 text-gray-800 antialiased">

    {{-- ═══════════════════════════════════════════
         Struktur Layout: Sidebar | Header + Content
         ═══════════════════════════════════════════ --}}
    <div class="flex h-full overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" x-init="window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) sidebarOpen = true;
    });">

        {{-- ── Sidebar (Partial) ── --}}
        @include('partials.sidebar')

        {{-- ── Area Utama: Header + Content ── --}}
        <div class="flex flex-1 flex-col overflow-hidden">

            {{-- Header (Partial) --}}
            @include('partials.header')

            {{-- Scrollable Content Area --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
                @if (session('success'))
                    <div
                        class="mb-6 flex items-center gap-3 rounded-lg border border-brand-200 bg-brand-50 px-4 py-3 text-sm text-brand-800 animate-slide-up">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 animate-slide-up">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer (Partial) --}}
            @include('partials.footer')

        </div>
    </div>

    @stack('scripts')

</body>

</html>
