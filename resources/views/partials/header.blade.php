{{-- ══════════════════════════════════════════════
     Header — Top Bar
     ══════════════════════════════════════════════ --}}
<header
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center border-b border-gray-200 bg-white/80 backdrop-blur-md px-4 md:px-6">

    {{-- Tombol toggle sidebar (mobile) --}}
    <button class="mr-3 rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden"
        @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar">
        <x-icon name="menu" class="w-5 h-5" />
    </button>

    {{-- Judul Halaman --}}
    <div class="flex-1">
        <h1 class="text-base font-semibold text-gray-900 md:text-lg">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    {{-- Kanan: Notifikasi + Profil --}}
    <div class="flex items-center gap-2">

        {{-- Tombol Notifikasi (placeholder) --}}
        <button class="relative rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700"
            aria-label="Notifikasi">
            <x-icon name="bell" class="w-5 h-5" />
            {{-- Badge notifikasi --}}
            <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        </button>

        {{-- Separator --}}
        <div class="hidden h-6 w-px bg-gray-200 sm:block"></div>

        {{-- Info User (desktop) --}}
        <div class="hidden items-center gap-2.5 sm:flex">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-medium text-gray-700 leading-tight">{{ auth()->user()->name }}</span>
                <span class="text-[11px] text-gray-400">{{ auth()->user()->roleLabel() }}</span>
            </div>
        </div>
    </div>
</header>
