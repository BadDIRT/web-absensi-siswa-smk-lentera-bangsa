@extends('layouts.app')

@section('title', 'Dashboard Administrator — SMK Lentera Bangsa')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6 animate-fade-in">

        {{-- ── Welcome Banner ── --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-700 via-brand-800 to-surface-800 p-6 md:p-8">
            {{-- Dekorasi background --}}
            <div class="pointer-events-none absolute -right-8 -top-8 h-48 w-48 rounded-full bg-brand-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-12 -left-12 h-40 w-40 rounded-full bg-accent-500/8 blur-3xl">
            </div>

            <div class="relative">
                <p class="text-sm font-medium text-brand-300">Selamat Datang,</p>
                <h2 class="mt-1 text-2xl font-bold text-white sm:text-3xl">{{ auth()->user()->name }}</h2>
                <p class="mt-2 max-w-lg text-sm text-brand-200/70">
                    Kelola data siswa, kelas, jurusan, dan pantau absensi harian dari satu tempat.
                </p>
            </div>
        </div>

        {{-- ── Statistik Cards ── --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Card: Total Siswa --}}
            <div
                class="group rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:shadow-brand-500/5 hover:border-brand-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Siswa</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_siswa']) }}</p>
                    </div>
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition-colors group-hover:bg-brand-100">
                        <x-icon name="users" class="w-5 h-5" />
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-gray-400">
                    <span>Terdaftar aktif</span>
                </div>
            </div>

            {{-- Card: Hadir Hari Ini --}}
            <div
                class="group rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:shadow-green-500/5 hover:border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Hadir Hari Ini</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['hadir_hari_ini']) }}</p>
                    </div>
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600 transition-colors group-hover:bg-green-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-green-600">
                    <span>Kehadiran {{ date('d M Y') }}</span>
                </div>
            </div>

            {{-- Card: Tidak Hadir --}}
            <div
                class="group rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:shadow-red-500/5 hover:border-red-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Tidak Hadir</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['tidak_hadir']) }}</p>
                    </div>
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600 transition-colors group-hover:bg-red-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-red-500">
                    <span>Izin, Sakit, Alpa</span>
                </div>
            </div>

            {{-- Card: Total Kelas --}}
            <div
                class="group rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:shadow-amber-500/5 hover:border-amber-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Kelas</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_kelas']) }}</p>
                    </div>
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition-colors group-hover:bg-amber-100">
                        <x-icon name="building" class="w-5 h-5" />
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1 text-xs text-gray-400">
                    <span>Kelas aktif</span>
                </div>
            </div>

        </div>

        {{-- ── Area Bawah: Activity & Quick Actions ── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Aktivitas Terbaru --}}
            <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <span class="text-xs text-gray-400">Hari ini</span>
                </div>
                <div class="p-6">
                    {{-- Placeholder — data akan diisi nanti --}}
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-4">
                            <x-icon name="clock" class="w-6 h-6" />
                        </div>
                        <p class="text-sm font-medium text-gray-400">Belum ada aktivitas</p>
                        <p class="mt-1 text-xs text-gray-300">Aktivitas akan muncul saat sistem digunakan.</p>
                    </div>
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-800">Aksi Cepat</h3>
                </div>
                <div class="space-y-2 p-4">
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="users" class="w-4 h-4" />
                        Tambah Siswa Baru
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="barcode" class="w-4 h-4" />
                        Generate Barcode
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="chart" class="w-4 h-4" />
                        Laporan Bulanan
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="building" class="w-4 h-4" />
                        Kelola Kelas
                    </a>
                </div>
            </div>

        </div>

    </div>
@endsection
