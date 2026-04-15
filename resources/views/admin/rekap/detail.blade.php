@extends('layouts.app')

@section('title', 'Detail Rekap — SMK Lentera Bangsa')
@section('page-title', 'Detail Absensi per Siswa')

@section('content')
    <div class="animate-fade-in">

        <x-page-header :title="$kelas->nama . ' — ' . $tanggal" :back="route('admin.rekap.index', ['tanggal' => $tanggal])" />

        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">NIS</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Status
                        </th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Masuk</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Pulang</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswas as $siswa)
                        @php
                            $absen = $siswa->absensis->first();
                            $status = $absen ? $absen->status : null;
                            $color = match ($status) {
                                'hadir' => 'bg-green-50 text-green-700',
                                'izin' => 'bg-blue-50 text-blue-700',
                                'sakit' => 'bg-amber-50 text-amber-700',
                                'alpa' => 'bg-red-50 text-red-700',
                                default => 'bg-gray-50 text-gray-400',
                            };
                            $label = match ($status) {
                                'hadir' => 'Hadir',
                                'izin' => 'Izin',
                                'sakit' => 'Sakit',
                                'alpa' => 'Alpa',
                                default => 'Belum',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $siswa->nis }}</td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $siswa->nama }}</td>
                            <td class="px-5 py-3 text-center">
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $color }}">{{ $label }}</span>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $absen?->jam_masuk ?? '—' }}</td>
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $absen?->jam_pulang ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-400">{{ $absen?->keterangan ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-sm text-gray-400">Tidak ada siswa aktif di
                                kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
