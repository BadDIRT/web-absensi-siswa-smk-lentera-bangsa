@extends('layouts.app')

@section('title', 'Rekap Absensi — SMK Lentera Bangsa')
@section('page-title', 'Rekap Absensi')

@section('content')
    <div class="animate-fade-in">

        <x-page-header title="Rekap Absensi Harian" />

        {{-- Filter --}}
        <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">Jurusan</label>
                <select name="jurusan_id" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    <option value="">Semua</option>
                    @foreach ($jurusans as $j)
                        <option value="{{ $j->id }}" {{ $jurusanId == $j->id ? 'selected' : '' }}>{{ $j->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-500">Kelas</label>
                <select name="kelas_id" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    <option value="">Semua</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 transition-colors">Tampilkan</button>
            @if (request('tanggal') || request('jurusan_id') || request('kelas_id'))
                <a href="{{ route('admin.rekap.index') }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors">Reset</a>
            @endif
        </form>

        {{-- Ringkasan Total --}}
        @if ($totalAll)
            <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-5">
                <div class="rounded-lg bg-gray-50 px-4 py-3 text-center">
                    <p class="text-xl font-bold text-gray-800">{{ $totalAll['total'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400">Total</p>
                </div>
                <div class="rounded-lg bg-green-50 px-4 py-3 text-center">
                    <p class="text-xl font-bold text-green-700">{{ $totalAll['hadir'] ?? 0 }}</p>
                    <p class="text-xs text-green-600">Hadir</p>
                </div>
                <div class="rounded-lg bg-blue-50 px-4 py-3 text-center">
                    <p class="text-xl font-bold text-blue-700">{{ $totalAll['izin'] ?? 0 }}</p>
                    <p class="text-xs text-blue-600">Izin</p>
                </div>
                <div class="rounded-lg bg-amber-50 px-4 py-3 text-center">
                    <p class="text-xl font-bold text-amber-700">{{ $totalAll['sakit'] ?? 0 }}</p>
                    <p class="text-xs text-amber-600">Sakit</p>
                </div>
                <div class="rounded-lg bg-red-50 px-4 py-3 text-center">
                    <p class="text-xl font-bold text-red-700">{{ $totalAll['alpa'] ?? 0 }}</p>
                    <p class="text-xs text-red-600">Alpa</p>
                </div>
            </div>
        @endif

        {{-- Tabel Rekap per Kelas --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Total
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Hadir
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Izin
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Sakit
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Alpa
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Belum
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-right">Detail
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rekap as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $item['kelas']->nama }}</td>
                            <td class="px-6 py-3 text-center text-gray-600">{{ $item['total'] }}</td>
                            <td class="px-6 py-3 text-center font-medium text-green-700">{{ $item['hadir'] }}</td>
                            <td class="px-6 py-3 text-center font-medium text-blue-700">{{ $item['izin'] }}</td>
                            <td class="px-6 py-3 text-center font-medium text-amber-700">{{ $item['sakit'] }}</td>
                            <td class="px-6 py-3 text-center font-medium text-red-700">{{ $item['alpa'] }}</td>
                            <td class="px-6 py-3 text-center text-gray-400">{{ $item['belum'] }}</td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('admin.rekap.detail', ['tanggal' => $tanggal, 'kelas_id' => $item['kelas']->id]) }}"
                                    class="text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-sm text-gray-400">Tidak ada data untuk
                                tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
