@extends('layouts.app')

@section('title', 'Detail Rekap — SMK Lentera Bangsa')
@section('page-title', 'Detail Absensi per Siswa')

@section('content')
    <div class="animate-fade-in">

        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $kelas->nama }}
                    <span class="text-sm font-normal text-gray-400">({{ $kelas->tahun_ajaran }})</span>
                </h2>
                <p class="text-sm text-gray-500">{{ Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
            </div>

            {{-- TOMBOL COPY WHATSAPP --}}
            <button onclick="copyToWhatsApp()" id="btn-copy"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-green-500 active:scale-[0.97]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                </svg>
                Salin untuk Grup WA
            </button>
        </div>

        {{-- Ringkasan Statistik Mini --}}
        <div class="mb-6 grid grid-cols-3 gap-3 sm:grid-cols-6">
            <div class="rounded-lg bg-gray-50 px-3 py-2.5 text-center">
                <p class="text-lg font-bold text-gray-800">{{ $stats['total'] }}</p>
                <p class="text-[11px] text-gray-400">Total</p>
            </div>
            <div class="rounded-lg bg-green-50 px-3 py-2.5 text-center">
                <p class="text-lg font-bold text-green-700">{{ $stats['hadir'] }}</p>
                <p class="text-[11px] text-green-600">Hadir</p>
            </div>
            <div class="rounded-lg bg-blue-50 px-3 py-2.5 text-center">
                <p class="text-lg font-bold text-blue-700">{{ $stats['izin'] }}</p>
                <p class="text-[11px] text-blue-600">Izin</p>
            </div>
            <div class="rounded-lg bg-amber-50 px-3 py-2.5 text-center">
                <p class="text-lg font-bold text-amber-700">{{ $stats['sakit'] }}</p>
                <p class="text-[11px] text-amber-600">Sakit</p>
            </div>
            <div class="rounded-lg bg-red-50 px-3 py-2.5 text-center">
                <p class="text-lg font-bold text-red-700">{{ $stats['alpa'] }}</p>
                <p class="text-[11px] text-red-600">Alpa</p>
            </div>
            <div class="rounded-lg bg-slate-100 px-3 py-2.5 text-center">
                <p class="text-lg font-bold text-slate-600">{{ $stats['belum_absen'] }}</p>
                <p class="text-[11px] text-slate-500">Belum</p>
            </div>
        </div>

        {{-- Tabel Detail --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">NIS</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">
                            Status</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Masuk</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jam Pulang</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Keterangan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-right">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswas as $siswa)
                        @php
                            $absen = $siswa->absensis->first();
                            $status = $absen ? $absen->status : null;

                            // PERBAIKAN: Tambahkan belum_absen
                            $color = match ($status) {
                                'hadir' => 'bg-green-50 text-green-700',
                                'izin' => 'bg-blue-50 text-blue-700',
                                'sakit' => 'bg-amber-50 text-amber-700',
                                'alpa' => 'bg-red-50 text-red-700',
                                'belum_absen' => 'bg-slate-100 text-slate-600',
                                default => 'bg-gray-50 text-gray-400',
                            };
                            $label = match ($status) {
                                'hadir' => 'Hadir',
                                'izin' => 'Izin',
                                'sakit' => 'Sakit',
                                'alpa' => 'Alpa',
                                'belum_absen' => 'Belum Absen',
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
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.rekap.siswa', $siswa->id) }}?dari={{ $tanggal }}&sampai={{ $tanggal }}"
                                    class="text-xs font-semibold text-brand-600 hover:text-brand-700 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- PERBAIKAN: Colspan diubah jadi 6 --}}
                            <td colspan="6" class="px-6 py-16 text-center text-sm text-gray-400">Tidak ada siswa aktif di
                                kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    @push('scripts')
        <script>
            function copyToWhatsApp() {
                const btn = document.getElementById('btn-copy');
                const originalText = btn.innerHTML;

                // Keamanan: Ganti button jadi "Menyalin..." agar tidak dobel klik
                btn.disabled = true;
                btn.innerHTML =
                    `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyalin...`;

                // MAGIC TERJADI DI SINI: Generate teks menggunakan data Blade langsung
                let text = `*REKAP ABSENSI HARIAN*\n`;
                text += `Kelas: *{{ $kelas->nama }}* ({{ $kelas->tahun_ajaran }})\n`;
                text += `Tanggal: {{ Carbon\Carbon::parse($tanggal)->format('d F Y') }}\n\n`;

                text +=
                    `Total: {{ $stats['total'] }} | Hadir: {{ $stats['hadir'] }} | Izin: {{ $stats['izin'] }} | Sakit: {{ $stats['sakit'] }} | Alpa: {{ $stats['alpa'] }} | Belum: {{ $stats['belum_absen'] }}\n\n`;

                text += `*Detail Siswa:*\n`;

                @foreach ($siswas as $index => $siswa)
                    @php
                        $absen = $siswa->absensis->first();
                        $statusWA = $absen ? $absen->statusLabel() : 'Belum';
                        $jamWA = $absen && $absen->jam_masuk ? $absen->jam_masuk . ($absen->jam_pulang ? ' - ' . $absen->jam_pulang : '') : '';
                        $ketWA = $absen && $absen->keterangan ? '(' . $absen->keterangan . ')' : '';
                    @endphp
                    text +=
                        `{{ $index + 1 }}. {{ $siswa->nama }} - *{{ $statusWA }}* {{ $jamWA }} {{ $ketWA }}\n`;
                @endforeach

                // Salin ke clipboard
                navigator.clipboard.writeText(text.trim()).then(() => {
                    btn.innerHTML =
                        `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg> Berhasil Disalin!`;
                    btn.classList.remove('bg-green-600', 'hover:bg-green-500');
                    btn.classList.add('bg-slate-700');

                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        btn.classList.remove('bg-slate-700');
                        btn.classList.add('bg-green-600', 'hover:bg-green-500');
                    }, 2000);
                }).catch(err => {
                    alert('Gagal menyalin, coba reload halaman.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }
        </script>
    @endpush
@endsection
