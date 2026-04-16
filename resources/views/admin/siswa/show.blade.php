@extends('layouts.app')

@section('title', 'Detail Siswa — SMK Lentera Bangsa')
@section('page-title', 'Detail Siswa')

@section('content')
    <div class="animate-fade-in max-w-4xl">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-1.5 text-xs text-gray-400">
            <a href="{{ route('admin.siswa.index') }}" class="hover:text-gray-600 transition-colors">Data Siswa</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-600">{{ $siswa->nama }}</span>
        </nav>

        {{-- Header Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-brand-700 to-brand-800 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-bold text-white">
                        {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-white truncate">{{ $siswa->nama }}</h2>
                        <p class="text-sm text-brand-200/70">
                            NIS {{ $siswa->nis }}
                            @if ($siswa->nipd)
                                · NIPD {{ $siswa->nipd }}
                            @endif
                            · {{ $siswa->kelas->nama }}
                        </p>
                    </div>
                    <div class="ml-auto shrink-0">
                        <span
                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                            {{ $siswa->status === 'aktif' ? 'bg-green-400/20 text-green-100' : 'bg-white/10 text-white/60' }}">
                            {{ $siswa->statusLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="border-b border-gray-100 px-6 py-3 flex items-center gap-2 bg-gray-50/50">
                <a href="{{ route('admin.siswa.edit', $siswa) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Edit Data
                </a>
                <a href="{{ route('admin.siswa.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Detail Grid --}}
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Kolom Kiri: Info Pribadi --}}
                    <div class="space-y-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Informasi Pribadi</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Jenis Kelamin</span>
                                <span
                                    class="text-sm text-gray-800">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Tempat Lahir</span>
                                <span class="text-sm text-gray-800">{{ $siswa->tempat_lahir ?? '—' }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Tanggal Lahir</span>
                                <span
                                    class="text-sm text-gray-800">{{ $siswa->tanggal_lahir?->format('d F Y') ?? '—' }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">No. Telepon</span>
                                <span class="text-sm text-gray-800">{{ $siswa->no_telepon ?? '—' }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Alamat</span>
                                <span class="text-sm text-gray-800 leading-relaxed">{{ $siswa->alamat ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Info Akademis + Akun --}}
                    <div class="space-y-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Informasi Akademis</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Kelas</span>
                                <span class="text-sm font-medium text-gray-800">{{ $siswa->kelas->nama }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Jurusan</span>
                                <span class="text-sm text-gray-800">{{ $siswa->kelas->jurusan->nama }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Tahun Ajaran</span>
                                <span class="text-sm text-gray-800">{{ $siswa->kelas->tahun_ajaran }}</span>
                            </div>
                        </div>

                        {{-- Info Akun --}}
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 pt-2">Akun Login</h3>
                        @if ($siswa->user)
                            <div class="rounded-lg border border-green-100 bg-green-50/50 p-3 space-y-2">
                                <div class="flex items-start gap-3">
                                    <span class="w-20 shrink-0 text-xs text-green-600 pt-0.5">Username</span>
                                    <span
                                        class="text-sm font-mono font-medium text-gray-800">{{ $siswa->user->username }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="w-20 shrink-0 text-xs text-green-600 pt-0.5">Email</span>
                                    <span class="text-sm text-gray-700">{{ $siswa->user->email }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="w-20 shrink-0 text-xs text-green-600 pt-0.5">Status</span>
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        Aktif
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-3">
                                <p class="text-xs text-gray-400">Siswa ini belum memiliki akun login.</p>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- Barcode Section --}}
                @if ($barcodeImage)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Kode Barcode</h3>
                        <div
                            class="inline-flex flex-col items-center gap-2 rounded-xl border border-gray-100 bg-gray-50/50 px-6 py-4">
                            <img src="{{ $barcodeImage }}" alt="Barcode {{ $siswa->no_barcode }}" class="h-14 w-auto">
                            <span class="font-mono text-[11px] text-gray-400">{{ $siswa->no_barcode }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Rekap Absensi Bulan Ini --}}
        <div class="rounded-xl border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-800">Rekap Absensi Bulan Ini</h3>
                <span
                    class="text-xs text-gray-400">{{ Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</span>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-4 border-b border-gray-100">
                <div class="px-5 py-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['hadir'] }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Hadir</p>
                </div>
                <div class="px-5 py-4 text-center border-l border-gray-100">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['izin'] }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Izin</p>
                </div>
                <div class="px-5 py-4 text-center border-l border-gray-100">
                    <p class="text-2xl font-bold text-amber-600">{{ $stats['sakit'] }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Sakit</p>
                </div>
                <div class="px-5 py-4 text-center border-l border-gray-100">
                    <p class="text-2xl font-bold text-red-600">{{ $stats['alpa'] }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Alpa</p>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                @forelse($absensis as $absen)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50/50 transition-colors">
                        <div class="w-20 shrink-0">
                            <p class="text-xs font-medium text-gray-700">{{ $absen->tanggal->format('d M') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $absen->tanggal->format('l') }}</p>
                        </div>
                        <div class="flex-1 flex items-center gap-3">
                            <span class="text-xs font-mono text-gray-500">{{ $absen->jam_masuk }}</span>
                            @if ($absen->jam_pulang)
                                <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                                <span class="text-xs font-mono text-gray-500">{{ $absen->jam_pulang }}</span>
                            @endif
                        </div>
                        <span
                            class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-medium
                            {{ $absen->status === 'hadir'
                                ? 'bg-green-50 text-green-700'
                                : ($absen->status === 'izin'
                                    ? 'bg-blue-50 text-blue-700'
                                    : ($absen->status === 'sakit'
                                        ? 'bg-amber-50 text-amber-700'
                                        : 'bg-red-50 text-red-700')) }}">
                            {{ $absen->statusLabel() }}
                        </span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">Belum ada data absensi bulan ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
