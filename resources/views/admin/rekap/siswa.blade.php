@extends('layouts.app')

@section('title', 'Rekap Siswa — SMK Lentera Bangsa')
@section('page-title', 'Rekap Absensi Individu')

@section('head')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .img-preview {
            transition: transform 0.2s ease;
        }

        .img-preview:hover {
            transform: scale(1.05);
        }
    </style>
@endsection

@section('content')
    <div class="animate-fade-in" x-data="{ showAll: false }">

        {{-- Header Info Siswa --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $siswa->nama }}</h2>
                <p class="text-sm text-gray-500">
                    NIS: {{ $siswa->nis }} · {{ $siswa->kelas->nama }} ({{ $siswa->kelas->tahun_ajaran }})
                </p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $siswa->kelas->jurusan->nama }}</p>
            </div>
            <a href="{{ route('admin.rekap.detail', ['kelas_id' => $siswa->kelas_id, 'tanggal' => $dari->toDateString()]) }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Rekap Kelas
            </a>
        </div>

        {{-- Filter Tanggal --}}
        <form method="GET" class="mb-6 flex flex-wrap items-end gap-3 rounded-xl border border-gray-200 bg-white p-4">
            <input type="hidden" name="from_detail" value="1">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari->toDateString() }}"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai->toDateString() }}"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>
            <button type="submit"
                class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.rekap.siswa', $siswa->id) }}"
                class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors">Reset</a>
        </form>

        {{-- Statistik Cards --}}
        <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-5">
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
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center col-span-2 sm:col-span-1">
                <p class="text-2xl font-bold text-slate-600">{{ $stats['belum_absen'] }}</p>
                <p class="mt-1 text-xs font-medium text-slate-500">Belum Absen</p>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Tanggal</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">
                            Status</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Masuk</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Pulang</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Keterangan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Bukti
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($absensis as $index => $absen)
                        @php
                            $color = match ($absen->status) {
                                'hadir' => 'bg-green-50 text-green-700',
                                'izin' => 'bg-blue-50 text-blue-700',
                                'sakit' => 'bg-amber-50 text-amber-700',
                                'alpa' => 'bg-red-50 text-red-700',
                                default => 'bg-gray-50 text-gray-500',
                            };
                        @endphp

                        {{-- LOGIKA SHOW MORE: Hanya tampilkan 5 item pertama, sisanya sembunyikan --}}
                        <tr class="hover:bg-gray-50/50 transition-colors"
                            @if ($index >= 5) x-cloak x-show="showAll" @endif>
                            <td class="px-5 py-3 text-gray-700">{{ $absen->tanggal->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $color }}">
                                    {{ $absen->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $absen->jam_masuk ?? '—' }}</td>
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $absen->jam_pulang ?? '—' }}</td>

                            {{-- KOLOM KETERANGAN: Tampil jika Izin/Sakit --}}
                            <td class="px-5 py-3 text-sm text-gray-600 max-w-[250px]">
                                {{ $absen->keterangan ?? '—' }}
                            </td>

                            {{-- KOLOM BUKTI: Preview gambar hanya jika Sakit --}}
                            <td class="px-5 py-3 text-center">
                                @if ($absen->status === 'sakit' && $absen->foto_surat)
                                    <button onclick="openImageModal('{{ Storage::url($absen->foto_surat) }}')"
                                        class="inline-block">
                                        <img src="{{ Storage::url($absen->foto_surat) }}" alt="Surat Sakit"
                                            class="img-preview h-10 w-10 rounded-lg object-cover border border-gray-200 shadow-sm cursor-pointer">
                                    </button>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-sm text-gray-400">
                                Tidak ada catatan absensi (selain belum absen) pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TOMBOL SHOW MORE --}}
        @if ($absensis->count() > 5)
            <div class="mt-4 text-center">
                <button @click="showAll = !showAll"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition-all hover:bg-gray-50 hover:text-gray-800">
                    <svg x-show="!showAll" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <svg x-show="showAll" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                    <span
                        x-text="showAll ? 'Sembunyikan' : 'Tampilkan Lebih Banyak ({{ $absensis->count() - 5 }})'"></span>
                </button>
            </div>
        @endif

    </div>

    {{-- MODAL PREVIEW GAMBAR --}}
    <div id="imageModal" x-data="{ isOpen: false, imgUrl: '' }" @open-image.window="isOpen=true; imgUrl=$event.detail" x-show="isOpen"
        x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
        @click.self="isOpen=false" x-transition>

        <div class="relative max-w-3xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between bg-gray-50 px-5 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Preview Surat Sakit</h3>
                <button @click="isOpen=false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4 bg-gray-100 flex items-center justify-center min-h-[300px]">
                <img :src="imgUrl" class="max-h-[75vh] w-auto rounded-lg shadow-md object-contain">
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Fungsi untuk memicu event buka modal (karena modal pakai Alpine)
            function openImageModal(url) {
                window.dispatchEvent(new CustomEvent('open-image', {
                    detail: url
                }));
            }
        </script>
    @endpush
@endsection
