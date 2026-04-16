@extends('layouts.app')

@section('title', 'Detail Jurusan — SMK Lentera Bangsa')
@section('page-title', 'Detail Jurusan')

@section('content')
    <div class="animate-fade-in max-w-4xl">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-1.5 text-xs text-gray-400">
            <a href="{{ route('admin.jurusan.index') }}" class="hover:text-gray-600 transition-colors">Data Jurusan</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-600">{{ $jurusan->nama }}</span>
        </nav>

        {{-- Header Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-brand-700 to-brand-800 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-bold text-white">
                        {{ $jurusan->kode }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-white truncate">{{ $jurusan->nama }}</h2>
                        <p class="text-sm text-brand-200/70">
                            Kode: {{ $jurusan->kode }} · {{ $jurusan->kelases->count() }} kelas · {{ $totalSiswa }} siswa
                            aktif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="border-b border-gray-100 px-6 py-3 flex items-center gap-2 bg-gray-50/50">
                <a href="{{ route('admin.jurusan.edit', $jurusan) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Edit Jurusan
                </a>
                <a href="{{ route('admin.kelas.create') }}?jurusan_id={{ $jurusan->id }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kelas
                </a>
                <a href="{{ route('admin.jurusan.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-medium text-gray-600 hover:bg-white hover:text-gray-800 hover:shadow-sm transition-all border border-transparent hover:border-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Detail --}}
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="space-y-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Informasi Jurusan</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="w-24 shrink-0 text-xs text-gray-400 pt-0.5">Kode</span>
                                <span class="inline-flex rounded bg-brand-50 px-2 py-0.5 text-xs font-bold text-brand-700">
                                    {{ $jurusan->kode }}
                                </span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="w-24 shrink-0 text-xs text-gray-400 pt-0.5">Nama</span>
                                <span class="text-sm font-medium text-gray-800">{{ $jurusan->nama }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Statistik</h3>
                        <div class="space-y-3">
                            <div
                                class="rounded-lg border border-gray-100 bg-gray-50/50 p-3 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Total Kelas</span>
                                <span class="text-lg font-bold text-gray-800">{{ $jurusan->kelases->count() }}</span>
                            </div>
                            <div
                                class="rounded-lg border border-green-100 bg-green-50/50 p-3 flex items-center justify-between">
                                <span class="text-xs text-green-600">Siswa Aktif</span>
                                <span class="text-lg font-bold text-green-600">{{ $totalSiswa }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Keterangan</h3>
                        <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-3">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ $jurusan->keterangan ?? 'Tidak ada keterangan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Kelas --}}
        <div class="rounded-xl border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-800">Daftar Kelas</h3>
                <span class="text-xs text-gray-400">{{ $jurusan->kelases->count() }} kelas</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama Kelas
                            </th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Tingkat</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Tahun Ajaran
                            </th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">
                                Siswa Aktif</th>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($jurusan->kelases as $kelas)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.kelas.show', $kelas) }}"
                                        class="font-medium text-gray-800 hover:text-brand-600 transition-colors">
                                        {{ $kelas->nama }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">Kelas {{ $kelas->tingkat }}</td>
                                <td class="px-5 py-3 text-gray-500 text-xs">{{ $kelas->tahun_ajaran }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span
                                        class="inline-flex items-center justify-center h-7 min-w-[28px] rounded-full bg-brand-50 px-2 text-xs font-semibold text-brand-700">
                                        {{ $kelas->siswa_aktif_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.kelas.show', $kelas) }}"
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
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                                            <x-icon name="building" class="w-5 h-5" />
                                        </div>
                                        <p class="text-sm text-gray-400">Belum ada kelas di jurusan ini.</p>
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
