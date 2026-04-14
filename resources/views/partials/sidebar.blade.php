{{-- ══════════════════════════════════════════════
     Sidebar — SMK Lentera Bangsa
     Menu dinamis berdasarkan role user.
     ══════════════════════════════════════════════ --}}
@php
    // Definisikan menu per role
    $sidebarMenus = match (auth()->user()->role) {
        'administrator' => [
            [
                'label' => 'Dashboard',
                'icon' => 'dashboard',
                'route' => 'dashboard.admin',
            ],
            [
                'label' => 'Data Siswa',
                'icon' => 'users',
                'route' => '#', // placeholder — nanti diimplementasi
            ],
            [
                'label' => 'Data Kelas',
                'icon' => 'building',
                'route' => '#',
            ],
            [
                'label' => 'Jurusan',
                'icon' => 'book',
                'route' => '#',
            ],
            [
                'label' => 'Barcode Siswa',
                'icon' => 'barcode',
                'route' => '#',
            ],
            [
                'label' => 'Rekap Absensi',
                'icon' => 'chart',
                'route' => '#',
            ],
            [
                'label' => 'Pengaturan',
                'icon' => 'gear',
                'route' => '#',
            ],
        ],
        'scanner' => [
            [
                'label' => 'Scan Absensi',
                'icon' => 'camera',
                'route' => 'dashboard.scanner',
            ],
            [
                'label' => 'Riwayat Scan',
                'icon' => 'clock',
                'route' => '#',
            ],
        ],
        'siswa' => [
            [
                'label' => 'Dashboard',
                'icon' => 'dashboard',
                'route' => 'dashboard.siswa',
            ],
            [
                'label' => 'Riwayat Absensi',
                'icon' => 'clock',
                'route' => '#',
            ],
            [
                'label' => 'Profil Saya',
                'icon' => 'user',
                'route' => '#',
            ],
        ],
        default => [],
    };
@endphp

{{-- Overlay gelap untuk mobile --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false"
    aria-hidden="true"></div>

{{-- Sidebar Panel --}}
<aside x-show="sidebarOpen" x-transition:enter="transition-transform ease-out duration-300"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition-transform ease-in duration-200" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-surface-800 lg:relative lg:z-auto lg:translate-x-0"
    aria-label="Sidebar navigasi">
    {{-- ── Brand Area ── --}}
    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-white">
            <x-icon name="lentera" class="w-5 h-5" />
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-bold leading-tight text-white tracking-wide">SMK Lentera Bangsa</span>
            <span class="text-[10px] font-medium uppercase tracking-widest text-brand-400">Sistem Absensi</span>
        </div>
        {{-- Tombol tutup (mobile saja) --}}
        <button class="ml-auto rounded-lg p-1.5 text-gray-400 hover:bg-white/10 hover:text-white lg:hidden"
            @click="sidebarOpen = false" aria-label="Tutup sidebar">
            <x-icon name="x" class="w-5 h-5" />
        </button>
    </div>

    {{-- ── Menu Navigasi ── --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <ul class="space-y-1">
            @foreach ($sidebarMenus as $menu)
                <li>
                    <a href="{{ $menu['route'] }}"
                        class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150
                            {{ request()->routeIs($menu['route']) ? 'bg-brand-600/20 text-brand-400' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                        <x-icon name="{{ $menu['icon'] }}"
                            class="w-5 h-5 shrink-0
                            {{ request()->routeIs($menu['route']) ? 'text-brand-400' : 'text-gray-500 group-hover:text-gray-400' }}" />
                        <span>{{ $menu['label'] }}</span>

                        {{-- Indikator aktif --}}
                        @if (request()->routeIs($menu['route']))
                            <span class="ml-auto h-1.5 w-1.5 rounded-full bg-brand-400"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- ── User Info (Bagian Bawah) ── --}}
    <div class="border-t border-white/10 px-4 py-4">
        <div class="flex items-center gap-3">
            {{-- Avatar inisial --}}
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-600/30 text-sm font-bold text-brand-400">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex flex-col min-w-0">
                <span class="truncate text-sm font-medium text-gray-200">{{ auth()->user()->name }}</span>
                <span class="text-[11px] font-medium text-brand-400/80">{{ auth()->user()->roleLabel() }}</span>
            </div>
        </div>

        {{-- Tombol Logout --}}
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
