{{-- ══════════════════════════════════════════════
     Sidebar — SMK Lentera Bangsa
     Menu dinamis berdasarkan role user.
     Tema: Deep Blue & Red Accent
     ══════════════════════════════════════════════ --}}
@php
    $sidebarMenus = match (auth()->user()->role) {
        'administrator' => [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard.admin'],
            ['label' => 'Data Siswa', 'icon' => 'users', 'route' => 'admin.siswa.index'],
            ['label' => 'Data Kelas', 'icon' => 'building', 'route' => 'admin.kelas.index'],
            ['label' => 'Jurusan', 'icon' => 'book', 'route' => 'admin.jurusan.index'],
            ['label' => 'Barcode Siswa', 'icon' => 'barcode', 'route' => 'admin.barcode.index'],
            ['label' => 'Cetak Barcode', 'icon' => 'print', 'route' => 'admin.barcode.print'],
            ['label' => 'Rekap Absensi', 'icon' => 'chart', 'route' => 'admin.rekap.index'],
            ['label' => 'Naik / Turun Kelas', 'icon' => 'arrow-up', 'route' => 'admin.naik-kelas.index'],
            ['label' => 'Pengaturan', 'icon' => 'gear', 'route' => 'admin.pengaturan.index'],
        ],
        'scanner' => [
            ['label' => 'Scan Absensi', 'icon' => 'camera', 'route' => 'dashboard.scanner'],
            ['label' => 'Riwayat Scan', 'icon' => 'clock', 'route' => 'scanner.riwayat.index'],
        ],
        'siswa' => [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard.siswa'],
            ['label' => 'Riwayat Absensi', 'icon' => 'clock', 'route' => 'siswa.riwayat.index'],
            ['label' => 'Profil Saya', 'icon' => 'user', 'route' => 'siswa.profil.index'],
        ],
        default => [],
    };

    // Tentukan route aktif saat ini
    $currentRoute = request()->route()->getName();
@endphp

{{-- Overlay gelap untuk mobile (Blur Effect) --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-75"
    x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-75"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
    @click="sidebarOpen = false" aria-hidden="true">
</div>

{{-- Sidebar Panel --}}
<aside x-show="sidebarOpen" x-transition:enter="transition-transform ease-out duration-300"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition-transform ease-in duration-200" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 flex w-[270px] flex-col bg-slate-900 shadow-2xl shadow-black/50 lg:relative lg:z-auto lg:translate-x-0 lg:shadow-none"
    aria-label="Sidebar navigasi">

    {{-- Garis Aksen Merah di Paling Atas --}}
    <div class="h-1 w-full bg-gradient-to-r from-red-600 via-red-500 to-blue-600"></div>

    {{-- ── Brand Area ── --}}
    <div class="flex h-16 items-center gap-3 border-b border-white/5 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white ring-1 ring-white/10">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Logo SMKS Lentera Bangsa"
                class="h-8 w-8 rounded">
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-bold leading-tight text-white tracking-wide">SMKS Lentera Bangsa</span>
            <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-red-400">Sistem Absensi</span>
        </div>
        <button class="ml-auto rounded-lg p-1.5 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden"
            @click="sidebarOpen = false" aria-label="Tutup sidebar">
            <x-icon name="x" class="w-5 h-5" />
        </button>
    </div>

    {{-- ── Menu Navigasi ── --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5 scrollbar-thin">
        <ul class="space-y-1">
            @foreach ($sidebarMenus as $menu)
                @php
                    $isActive =
                        $currentRoute === $menu['route'] ||
                        (str_starts_with($currentRoute, str_replace('.index', '.', $menu['route'])) &&
                            $currentRoute !== $menu['route']);
                @endphp
                <li>
                    <a href="{{ route($menu['route']) }}"
                        class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-medium transition-all duration-200
                            {{ $isActive
                                ? 'bg-blue-600/15 text-blue-400 shadow-sm shadow-blue-500/5'
                                : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">

                        {{-- Indikator Aktif (Garis Biru di kiri) --}}
                        @if ($isActive)
                            <span
                                class="absolute left-0 top-1/2 h-6 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-500"></span>
                        @endif

                        <x-icon name="{{ $menu['icon'] }}"
                            class="w-[18px] h-[18px] shrink-0 transition-colors
                            {{ $isActive ? 'text-blue-400' : 'text-slate-500 group-hover:text-slate-300' }}" />

                        <span class="truncate">{{ $menu['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- ── User Info ── --}}
    <div class="border-t border-white/10 px-4 py-4">
        <div class="flex items-center gap-3">
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-600/30 text-sm font-bold text-brand-400">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex flex-col min-w-0">
                <span class="truncate text-sm font-medium text-gray-200">{{ auth()->user()->name }}</span>
                <span class="text-[11px] font-medium text-brand-400/80">{{ auth()->user()->roleLabel() }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-500 transition-colors hover:bg-red-500/10 hover:text-red-400">
                <x-icon name="logout" class="w-4 h-4" />
                Keluar
            </button>
        </form>
    </div>
</aside>
