@extends('layouts.app')

@section('title', 'Naik Kelas - Angkatan')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.naik-kelas.index') }}" class="hover:text-gray-700">Naik Kelas</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">Angkatan</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Naik Kelas Angkatan</h1>
            <p class="mt-1 text-sm text-gray-500">Pilih kelas tujuan untuk setiap kelas asal, lalu proses.</p>
        </div>

        <!-- Panel Filter -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <form method="GET" action="{{ route('admin.naik-kelas.angkatan') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                    <select name="filter_tahun"
                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach ($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ request('filter_tahun') == $ta ? 'selected' : '' }}>
                                {{ $ta }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                    <select name="filter_tingkat"
                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tingkat</option>
                        <option value="10" {{ request('filter_tingkat') == 10 ? 'selected' : '' }}>10</option>
                        <option value="11" {{ request('filter_tingkat') == 11 ? 'selected' : '' }}>11</option>
                        <option value="12" {{ request('filter_tingkat') == 12 ? 'selected' : '' }}>12</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                    <select name="filter_jurusan"
                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Jurusan</option>
                        @foreach ($jurusans as $j)
                            <option value="{{ $j->id }}" {{ request('filter_jurusan') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nama Kelas</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: RPL 1"
                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-900 transition-colors">
                        Filter
                    </button>
                    @if (request('filter_tahun') || request('filter_tingkat') || request('filter_jurusan') || request('search'))
                        <a href="{{ route('admin.naik-kelas.angkatan') }}"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors"
                            title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if ($kelases->isEmpty())
            <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="mt-2 text-gray-500">
                    @if (request()->hasAny(['filter_tahun', 'filter_tingkat', 'filter_jurusan', 'search']))
                        Tidak ada kelas yang cocok dengan filter.
                    @else
                        Tidak ada kelas yang memiliki siswa aktif.
                    @endif
                </p>
            </div>
        @else
            {{-- Form Proses --}}
            <form method="POST" action="{{ route('admin.naik-kelas.angkatan.store') }}" x-data="{ showModal: false }"
                x-ref="formAngkatan">
                @csrf

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kelas Asal</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jurusan</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tahun Ajaran</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Siswa Aktif</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pindah Ke</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($kelases as $index => $kelas)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $kelas->tingkat }}
                                                </span>
                                                <span class="font-medium text-gray-900">{{ $kelas->nama }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $kelas->jurusan->nama }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-md">{{ $kelas->tahun_ajaran }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[2rem] h-8 rounded-full text-sm font-medium {{ $kelas->siswa_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                                {{ $kelas->siswa_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="mapping[{{ $kelas->id }}]"
                                                class="rounded-lg border-gray-300 text-sm w-full max-w-xs focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">-- Tidak dipindahkan --</option>
                                                @foreach ($kelasTujuan->groupBy('tahun_ajaran') as $ta => $items)
                                                    @if ($items->where('id', '!=', $kelas->id)->isNotEmpty())
                                                        <optgroup label="Tahun Ajaran {{ $ta }}">
                                                            @foreach ($items as $kt)
                                                                @if ($kt->id !== $kelas->id)
                                                                    <option value="{{ $kt->id }}"
                                                                        {{ old("mapping.{$kelas->id}") == $kt->id ? 'selected' : '' }}>
                                                                        [{{ $kt->tingkat }}] {{ $kt->nama }} —
                                                                        {{ $kt->jurusan->nama }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-4">
                    <a href="{{ route('admin.naik-kelas.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Kembali
                    </a>
                    <button type="button" @click="showModal = true"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Proses Pindah
                    </button>
                </div>

                {{-- Popup Konfirmasi --}}
                <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50">
                    <div class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6" @click.stop>
                            <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-full bg-amber-100 mb-4">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 text-center">Konfirmasi Pemindahan</h3>
                            <p class="mt-2 text-sm text-gray-500 text-center">Apakah Anda yakin akan memproses pemindahan
                                siswa sesuai mapping yang dipilih?</p>
                            <div class="mt-6 flex gap-3">
                                <button type="button" @click="showModal = false"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                                <button type="button" @click="$refs.formAngkatan.submit()"
                                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">Ya,
                                    Proses</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection
