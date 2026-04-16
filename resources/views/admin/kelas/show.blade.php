@extends('layouts.app')

@section('title', 'Detail Kelas — SMK Lentera Bangsa')
@section('page-title', 'Detail Kelas')

@section('content')
    <div class="animate-fade-in max-w-4xl">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-1.5 text-xs text-gray-400">
            <a href="{{ route('admin.kelas.index') }}" class="hover:text-gray-600 transition-colors">Data Kelas</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-600">{{ $kela->nama }}</span>
        </nav>

        {{-- Header Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-brand-700 to-brand-800 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-bold text-white">
                        {{ strtoupper(substr($kela->nama, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-white truncate">{{ $kela->nama }}</h2>
                        <p class="text-sm text-brand-200/70">
                            {{ $kela->jurusan->nama }} · Kelas {{ $kela->tingkat }} · {{ $kela->tahun_ajaran }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="border-b border-gray-100 px-6 py-3 flex items-center gap-2 bg-gray-50/50">
                <a href="{{ route('admin.kelas.edit', $kela) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Edit Kelas
                </a>
                <a href="{{ route('admin.siswa.create') }}?kelas_id={{ $kela->id }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Siswa
                </a>
                <a href="{{ route('admin.kelas.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Detail --}}
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Informasi Kelas</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Nama Kelas</span>
                                <span class="text-sm font-medium text-gray-800">{{ $kela->nama }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Jurusan</span>
                                <span class="text-sm text-gray-800">{{ $kela->jurusan->nama }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Tingkat</span>
                                <span class="text-sm text-gray-800">Kelas {{ $kela->tingkat }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-28 shrink-0 text-xs text-gray-400 pt-0.5">Tahun Ajaran</span>
                                <span class="text-sm text-gray-800">{{ $kela->tahun_ajaran }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Statistik Siswa</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-lg border border-green-100 bg-green-50/50 p-3 text-center">
                                <p class="text-xl font-bold text-green-600">{{ $stats['aktif'] }}</p>
                                <p class="text-[11px] text-green-600/70 mt-0.5">Aktif</p>
                            </div>
                            <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-3 text-center">
                                <p class="text-xl font-bold text-gray-500">{{ $stats['tidak_aktif'] }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Tidak Aktif</p>
                            </div>
                            <div class="rounded-lg border border-amber-100 bg-amber-50/50 p-3 text-center">
                                <p class="text-xl font-bold text-amber-600">{{ $stats['pindah'] }}</p>
                                <p class="text-[11px] text-amber-600/70 mt-0.5">Pindah</p>
                            </div>
                            <div class="rounded-lg border border-blue-100 bg-blue-50/50 p-3 text-center">
                                <p class="text-xl font-bold text-blue-600">{{ $stats['lulus'] }}</p>
                                <p class="text-[11px] text-blue-600/70 mt-0.5">Lulus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Siswa Aktif --}}
        <div class="rounded-xl border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-800">Daftar Siswa Aktif</h3>
                <span class="text-xs text-gray-400">{{ $siswas->count() }} siswa</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">NIS</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">NIPD</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">JK</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Akun</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($siswas as $siswa)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $siswa->nis }}</td>
                                <td class="px-5 py-3">
                                    @if ($siswa->nipd)
                                        <span
                                            class="font-mono text-xs font-medium text-brand-600">{{ $siswa->nipd }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.siswa.show', $siswa) }}"
                                        class="font-medium text-gray-800 hover:text-brand-600 transition-colors">
                                        {{ $siswa->nama }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">
                                    {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td class="px-5 py-3">
                                    @if ($siswa->user_id)
                                        <span class="inline-flex items-center gap-1 text-xs text-green-600 font-medium">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Ada
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.siswa.show', $siswa) }}"
                                        class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-brand-600 transition-colors"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                                            <x-icon name="users" class="w-5 h-5" />
                                        </div>
                                        <p class="text-sm text-gray-400">Belum ada siswa aktif di kelas ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
