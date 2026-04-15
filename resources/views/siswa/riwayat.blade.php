@extends('layouts.app')

@section('title', 'Riwayat Absensi — SMK Lentera Bangsa')
@section('page-title', 'Riwayat Absensi')

@section('content')
    <div class="mx-auto max-w-4xl animate-fade-in">

        <x-page-header title="Riwayat Absensi" />

        @if (!$siswa)
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 text-center">
                <p class="text-sm font-medium text-amber-800">Data siswa belum terhubung dengan akun Anda.</p>
                <p class="mt-1 text-xs text-amber-600">Silakan hubungi administrator untuk menghubungkan akun dengan data
                    siswa.</p>
            </div>
        @else
            {{-- Filter Bulan --}}
            <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-500">Bulan</label>
                    <input type="month" name="bulan" value="{{ $bulan }}"
                        class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                </div>
                <button type="submit"
                    class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition-colors">Tampilkan</button>
                @if (request('bulan'))
                    <a href="{{ route('siswa.riwayat.index') }}"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors">Bulan
                        Ini</a>
                @endif
            </form>

            {{-- Stats --}}
            <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-xl border border-green-100 bg-green-50 p-4 text-center">
                    <p class="text-2xl font-bold text-green-700">{{ $stats['hadir'] }}</p>
                    <p class="mt-1 text-xs font-medium text-green-600">Hadir</p>
                </div>
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-center">
                    <p class="text-2xl font-bold text-blue-700">{{ $stats['izin'] }}</p>
                    <p class="mt-1 text-xs font-medium text-blue-600">Izin</p>
                </div>
                <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 text-center">
                    <p class="text-2xl font-bold text-amber-700">{{ $stats['sakit'] }}</p>
                    <p class="mt-1 text-xs font-medium text-amber-600">Sakit</p>
                </div>
                <div class="rounded-xl border border-red-100 bg-red-50 p-4 text-center">
                    <p class="text-2xl font-bold text-red-700">{{ $stats['alpa'] }}</p>
                    <p class="mt-1 text-xs font-medium text-red-600">Alpa</p>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Tanggal</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Masuk
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Pulang
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">
                                Status</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($absensis as $absen)
                            @php
                                $color = match ($absen->status) {
                                    'hadir' => 'bg-green-50 text-green-700',
                                    'izin' => 'bg-blue-50 text-blue-700',
                                    'sakit' => 'bg-amber-50 text-amber-700',
                                    'alpa' => 'bg-red-50 text-red-700',
                                    default => 'bg-gray-50 text-gray-500',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3 text-gray-700">{{ $absen->tanggal->format('d M Y') }}</td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-600">{{ $absen->jam_masuk }}</td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-600">{{ $absen->jam_pulang ?? '—' }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $color }}">{{ $absen->statusLabel() }}</span>
                                </td>
                                <td class="px-6 py-3 text-gray-400">{{ $absen->keterangan ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <p class="text-sm text-gray-400">Tidak ada data absensi untuk bulan ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @endif

    </div>
@endsection
