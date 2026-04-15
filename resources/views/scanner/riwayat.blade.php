@extends('layouts.app')

@section('title', 'Riwayat Scan — SMK Lentera Bangsa')
@section('page-title', 'Riwayat Scan')

@section('content')
    <div class="animate-fade-in">

        <x-page-header title="Riwayat Scan" />

        {{-- Filter Tanggal --}}
        <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>
            <button type="submit"
                class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition-colors">Tampilkan</button>
            @if (request('tanggal'))
                <a href="{{ route('scanner.riwayat.index') }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors">Hari
                    Ini</a>
            @endif
        </form>

        {{-- Summary Cards --}}
        <div class="mb-6 grid grid-cols-2 gap-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Scan</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalScan }}</p>
            </div>
            <div class="rounded-xl border border-green-100 bg-green-50 p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-green-600">Hadir</p>
                <p class="mt-2 text-3xl font-bold text-green-700">{{ $totalHadir }}</p>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">NIS</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Masuk</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Pulang</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($absensis as $absensi)
                        @php
                            $color = match ($absensi->status) {
                                'hadir' => 'bg-green-50 text-green-700',
                                'izin' => 'bg-blue-50 text-blue-700',
                                'sakit' => 'bg-amber-50 text-amber-700',
                                'alpa' => 'bg-red-50 text-red-700',
                                default => 'bg-gray-50 text-gray-500',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 font-mono text-xs text-gray-600">{{ $absensi->siswa->nis }}</td>
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $absensi->siswa->nama }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $absensi->siswa->kelas->nama }}</td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-600">{{ $absensi->jam_masuk }}</td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-600">{{ $absensi->jam_pulang ?? '—' }}</td>
                            <td class="px-6 py-3">
                                <span
                                    class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $color }}">{{ $absensi->statusLabel() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                                        <x-icon name="clock" class="w-5 h-5" />
                                    </div>
                                    <p class="text-sm text-gray-400">Belum ada data scan untuk tanggal ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $absensis->withQueryString()->links() }}

    </div>
@endsection
