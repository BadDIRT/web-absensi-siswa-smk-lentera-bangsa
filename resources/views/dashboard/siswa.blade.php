@extends('layouts.app')

@section('title', 'Dashboard Siswa — SMK Lentera Bangsa')
@section('page-title', 'Dashboard Siswa')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6 animate-fade-in">

        {{-- ── Welcome Card ── --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-600 via-brand-700 to-brand-800 p-6 md:p-8">
            <div class="pointer-events-none absolute -right-6 -top-6 h-40 w-40 rounded-full bg-accent-500/10 blur-3xl"></div>
            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-brand-200">Selamat Datang,</p>
                    <h2 class="mt-1 text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="mt-1 text-sm text-brand-200/70">NIS: {{ $user->username ?? '—' }}</p>
                </div>
                {{-- Status hari ini --}}
                <div
                    class="flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2.5 backdrop-blur-sm ring-1 ring-white/10">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                    <span class="text-sm font-medium text-white">Belum Absen Hari Ini</span>
                </div>
            </div>
        </div>

        {{-- ── Statistik Bulan Ini ── --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-xl border border-green-100 bg-green-50 p-4 text-center">
                <p class="text-2xl font-bold text-green-700">{{ $absensiBulanIni['hadir'] }}</p>
                <p class="mt-1 text-xs font-medium text-green-600">Hadir</p>
            </div>

            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-center">
                <p class="text-2xl font-bold text-blue-700">{{ $absensiBulanIni['izin'] }}</p>
                <p class="mt-1 text-xs font-medium text-blue-600">Izin</p>
            </div>

            <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 text-center">
                <p class="text-2xl font-bold text-amber-700">{{ $absensiBulanIni['sakit'] }}</p>
                <p class="mt-1 text-xs font-medium text-amber-600">Sakit</p>
            </div>

            <div class="rounded-xl border border-red-100 bg-red-50 p-4 text-center">
                <p class="text-2xl font-bold text-red-700">{{ $absensiBulanIni['alpa'] }}</p>
                <p class="mt-1 text-xs font-medium text-red-600">Alpa</p>
            </div>

        </div>

        {{-- ── Riwayat Absensi ── --}}
        <div class="rounded-xl border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-800">Riwayat Absensi</h3>
                <span class="text-xs text-gray-400">Bulan {{ date('F Y') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Tanggal</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Masuk
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if ($riwayatAbsensi->isEmpty())
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                                            <x-icon name="clock" class="w-5 h-5" />
                                        </div>
                                        <p class="text-sm text-gray-400">Belum ada data absensi</p>
                                        <p class="mt-0.5 text-xs text-gray-300">Data akan muncul setelah Anda melakukan
                                            absensi.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($riwayatAbsensi as $absensi)
                                {{-- Placeholder row --}}
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-3.5 text-gray-700">{{ $absensi->tanggal ?? '—' }}</td>
                                    <td class="px-6 py-3.5 text-gray-700">{{ $absensi->jam_masuk ?? '—' }}</td>
                                    <td class="px-6 py-3.5">{{ $absensi->status ?? '—' }}</td>
                                    <td class="px-6 py-3.5 text-gray-400">{{ $absensi->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Info: Cara Absensi ── --}}
        <div class="rounded-xl border border-brand-100 bg-brand-50/50 p-5">
            <div class="flex gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-100 text-brand-600">
                    <x-icon name="barcode" class="w-5 h-5" />
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-brand-800">Cara Melakukan Absensi</h4>
                    <p class="mt-1 text-sm text-brand-700/70 leading-relaxed">
                        Tunjukkan barcode CODABAR Anda yang terdapat pada kartu siswa ke petugas scanner di gerbang sekolah.
                        Absensi akan tercatat secara otomatis saat barcode berhasil dipindai.
                    </p>
                </div>
            </div>
        </div>

    </div>
@endsection
