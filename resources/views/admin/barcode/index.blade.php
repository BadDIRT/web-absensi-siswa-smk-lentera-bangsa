@extends('layouts.app')

@section('title', 'Kartu Absensi Siswa — SMK Lentera Bangsa')
@section('page-title', 'Kartu Absensi Siswa')

@section('content')
    <div class="animate-fade-in">

        {{-- ── Info Bar ── --}}
        <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50/50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600 mt-0.5">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-800">Format Barcode Code 128</p>
                    <p class="mt-0.5 text-xs text-blue-700/70 leading-relaxed">
                        Kartu absensi siswa menggunakan Barcode 1D tipe Code 128 yang berisi NIPD (diapit huruf A).
                        Pastikan scanner fisik mendukung pembacaan format Code 128 dan posisi barcode lurus menghadap
                        scanner.
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Action Bar ── --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                    <x-icon name="barcode" class="w-5 h-5" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Kelola Kartu Absensi</p>
                    <p class="text-xs text-gray-400">Generate kode per siswa atau cetak kartu absensi</p>
                </div>
            </div>
            <a href="{{ route('admin.barcode.print', array_filter(['kelas_id' => request('kelas_id')])) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-700 active:scale-[0.98]">
                <x-icon name="print" class="w-4 h-4" />
                Cetak / Download
            </a>
        </div>

        {{-- ── Filter ── --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS, NIPD, atau nama..."
                class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 w-full max-w-xs">
            <select name="kelas_id" onchange="this.form.submit()"
                class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <option value="">Semua Kelas</option>
                @foreach ($kelases as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}</option>
                @endforeach
            </select>
            <select name="filter" onchange="this.form.submit()"
                class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <option value="" {{ request('filter') === '' ? 'selected' : '' }}>Semua Status</option>
                <option value="has_barcode" {{ request('filter') === 'has_barcode' ? 'selected' : '' }}>Sudah Punya Kode
                </option>
                <option value="no_barcode" {{ request('filter') === 'no_barcode' ? 'selected' : '' }}>Belum Punya Kode
                </option>
            </select>
            @if (request('search') || request('kelas_id') || request('filter'))
                <a href="{{ route('admin.barcode.index') }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors">Reset</a>
            @endif
        </form>

        {{-- ── Tabel ── --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">NIS</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">NIPD</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Kelas</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Kode</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Barcode</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $siswa->nis }}</td>
                            <td class="px-5 py-3">
                                @if ($siswa->nipd)
                                    <span class="font-mono text-xs font-medium text-gray-800">{{ $siswa->nipd }}</span>
                                @else
                                    <span class="text-xs text-gray-400 italic">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $siswa->nama }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $siswa->kelas->nama }}</td>

                            {{-- KOLOM KODE --}}
                            <td class="px-5 py-3">
                                @if ($siswa->no_barcode)
                                    <span class="font-mono text-[10px] text-gray-400">{{ $siswa->no_barcode }}</span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- KOLOM BARCODE --}}
                            <td class="px-5 py-3">
                                @if ($siswa->no_barcode)
                                    <img src="data:image/png;base64, {{ base64_encode($generator->getBarcode($siswa->no_barcode, $generator::TYPE_CODE_128, 2, 40)) }}"
                                        alt="Barcode {{ $siswa->nipd }}"
                                        class="h-10 w-auto rounded border border-gray-100">
                                @else
                                    <span class="text-xs text-gray-300 italic">Belum ada</span>
                                @endif
                            </td>

                            {{-- KOLOM AKSI --}}
                            <td class="px-5 py-3 text-center">
                                @if (!$siswa->no_barcode && $siswa->nipd)
                                    <form method="POST" action="{{ route('admin.barcode.generate', $siswa) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium text-brand-600 hover:bg-brand-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Generate
                                        </button>
                                    </form>
                                @elseif(!$siswa->nipd)
                                    <span class="text-[11px] text-amber-500 font-medium">Belum punya NIPD</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] text-green-600 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        Ada
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                                        <x-icon name="barcode" class="w-5 h-5" />
                                    </div>
                                    <p class="text-sm text-gray-400">Tidak ada data siswa.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $siswas->withQueryString()->links() }}

    </div>
@endsection
