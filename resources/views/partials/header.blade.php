{{-- ══════════════════════════════════════════════
     Header — Top Bar
     ══════════════════════════════════════════════ --}}
<header x-data="{ profileOpen: false }" @click.away="profileOpen = false"
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-gray-200/80 bg-white/90 px-4 shadow-sm shadow-gray-100/50 md:px-6 backdrop-blur-md">

    {{-- Kiri: Toggle Sidebar + Judul Halaman --}}
    <div class="flex items-center gap-3">
        {{-- Tombol toggle sidebar (mobile) --}}
        <button class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden"
            @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar">
            <x-icon name="menu" class="w-5 h-5" />
        </button>

        {{-- Judul Halaman --}}
        <h1 class="text-base font-semibold text-gray-800 truncate md:text-lg">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    {{-- Kanan: Profil Dropdown --}}
    <div class="relative">
        {{-- Tombol Trigger Profil --}}
        <button @click="profileOpen = !profileOpen"
            class="flex items-center gap-3 rounded-xl px-2 py-1.5 transition-colors hover:bg-gray-100/80"
            :class="{ 'bg-gray-100': profileOpen }">

            {{-- Avatar --}}
            <div
                class="relative flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-brand-500 to-brand-600 text-xs font-bold text-white shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                {{-- Status Online Indicator (Opsional) --}}
                <span
                    class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>
            </div>

            {{-- Nama & Role (Hidden di mobile) --}}
            <div class="hidden flex-col items-start md:flex">
                <span class="text-sm font-medium text-gray-700 leading-tight">{{ auth()->user()->name }}</span>
                <span
                    class="text-[11px] font-medium text-brand-600 leading-tight">{{ auth()->user()->roleLabel() }}</span>
            </div>

            {{-- Chevron Icon --}}
            <svg class="hidden w-4 h-4 text-gray-400 transition-transform duration-200 md:block"
                :class="profileOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>

        {{-- Dropdown Menu --}}
        <div x-show="profileOpen" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" x-cloak
            class="absolute right-0 mt-2 w-64 origin-top-right rounded-xl border border-gray-200 bg-white py-2 shadow-lg shadow-gray-200/50 ring-1 ring-black/5">

            {{-- Info User di Dropdown --}}
            <div class="px-4 py-3 border-b border-gray-100 mb-1">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                <span
                    class="mt-2 inline-flex rounded-full bg-brand-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-brand-700">
                    {{ auth()->user()->roleLabel() }}
                </span>
            </div>

            {{-- Menu Edit Profil (Dinamis berdasarkan Role) --}}
            @if (auth()->user()->hasRole('siswa'))
                <a href="{{ route('siswa.profil.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <x-icon name="user" class="w-4 h-4 text-gray-400" />
                    Edit Profil Siswa
                </a>
            @else
                <a href="{{ route('admin.pengaturan.edit', auth()->user()->id) }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <x-icon name="user" class="w-4 h-4 text-gray-400" />
                    Edit Profil Pengguna
                </a>
            @endif

            {{-- Menu Ganti Password (Opsional, jika ada fitur nanti) --}}
            {{-- <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <x-icon name="lock-closed" class="w-4 h-4 text-gray-400" />
                Ubah Password
            </a> --}}

            {{-- Divider --}}
            <div class="my-1 border-t border-gray-100"></div>

            {{-- Menu Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit"
                    class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <x-icon name="logout" class="w-4 h-4" />
                    Keluar
                </button>
            </form>
        </div>
    </div>
</header>
