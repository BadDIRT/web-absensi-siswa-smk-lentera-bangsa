@extends('layouts.app')

@section('title', 'Dashboard Administrator — SMK Lentera Bangsa')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6 animate-fade-in">

        {{-- ── Welcome Banner ── --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-700 via-brand-800 to-surface-800 p-6 md:p-8">
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

            {{-- Total Siswa --}}
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
                <div class="mt-3 text-xs text-gray-400">Terdaftar aktif</div>
            </div>

            {{-- Hadir Hari Ini --}}
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
                <div class="mt-3 text-xs text-green-600">Kehadiran {{ date('d M Y') }}</div>
            </div>

            {{-- Tidak Hadir --}}
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
                <div class="mt-3 flex items-center gap-1.5 text-xs text-red-500">
                    <span>Izin, Sakit, Alpa</span>
                    @if ($stats['belum_absen'] > 0)
                        <span class="text-gray-300">·</span>
                        <span class="font-semibold text-red-600">{{ number_format($stats['belum_absen']) }}</span>
                        <span>belum absen</span>
                    @endif
                </div>
            </div>

            {{-- Total Kelas --}}
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
                <div class="mt-3 text-xs text-gray-400">Kelas aktif</div>
            </div>

        </div>

        {{-- ── Area Bawah ── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Absensi Terakhir --}}
            <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-800">Absensi Terakhir</h3>
                    <a href="{{ route('admin.rekap.index') }}"
                        class="text-xs font-medium text-brand-600 hover:text-brand-700 transition-colors">Lihat Rekap →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($absensiTerakhir as $absen)
                        @php
                            // PERBAIKAN: Warna badge dinamis berdasarkan status
                            $statusColor = match ($absen->status) {
                                'hadir' => 'bg-green-50 text-green-700',
                                'izin' => 'bg-blue-50 text-blue-700',
                                'sakit' => 'bg-amber-50 text-amber-700',
                                'alpa' => 'bg-red-50 text-red-700',
                                default => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition-colors">
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-50 text-xs font-bold text-brand-700">
                                {{ strtoupper(substr($absen->siswa->nama, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $absen->siswa->nama }}</p>
                                <p class="text-xs text-gray-400">{{ $absen->siswa->nis }} ·
                                    {{ $absen->siswa->kelas->nama }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs font-mono text-gray-600">
                                    {{ $absen->jam_masuk ?? '—' }}
                                    @if ($absen->jam_pulang)
                                        <span class="text-gray-400"> → {{ $absen->jam_pulang }}</span>
                                    @endif
                                </p>
                                <span
                                    class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium {{ $statusColor }}">
                                    {{ $absen->statusLabel() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-4">
                                <x-icon name="clock" class="w-6 h-6" />
                            </div>
                            <p class="text-sm font-medium text-gray-400">Belum ada absensi hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-800">Aksi Cepat</h3>
                </div>
                <div class="space-y-1 p-3">
                    <a href="{{ route('admin.siswa.create') }}"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="users" class="w-4 h-4" /> Tambah Siswa Baru
                    </a>
                    <a href="{{ route('admin.barcode.index') }}"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="barcode" class="w-4 h-4" /> Lihat Barcode Siswa
                    </a>
                    <a href="{{ route('admin.rekap.index') }}"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="chart" class="w-4 h-4" /> Rekap Absensi Hari Ini
                    </a>
                    <a href="{{ route('admin.kelas.create') }}"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="building" class="w-4 h-4" /> Tambah Kelas
                    </a>
                    <a href="{{ route('admin.jurusan.create') }}"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="book" class="w-4 h-4" /> Tambah Jurusan
                    </a>
                    <a href="{{ route('admin.pengaturan.index') }}"
                        class="flex items-center gap-3 rounded-lg p-3 text-sm text-gray-600 transition-colors hover:bg-brand-50 hover:text-brand-700">
                        <x-icon name="gear" class="w-4 h-4" /> Kelola Pengguna
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
