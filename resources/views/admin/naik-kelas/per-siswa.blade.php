@extends('layouts.app')

@section('title', 'Naik Kelas - Per Siswa')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.naik-kelas.index') }}" class="hover:text-gray-700">Naik Kelas</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">Per Siswa</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Naik Kelas Per Siswa</h1>
        </div>

        <div class="grid gap-6 lg:grid-cols-4">
            <!-- Panel Kiri: Filter -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 space-y-4">
                    <h3 class="font-semibold text-gray-900">Filter</h3>

                    <form method="GET" action="{{ route('admin.naik-kelas.per-siswa') }}" class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <select name="filter_kelas"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelases as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('filter_kelas') == $k->id ? 'selected' : '' }}>
                                        [{{ $k->tingkat }}] {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nama atau NIS..."
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                            Terapkan
                        </button>
                    </form>

                    @if (request('filter_kelas') || request('search'))
                        <a href="{{ route('admin.naik-kelas.per-siswa') }}"
                            class="block text-center text-sm text-blue-600 hover:text-blue-800">
                            Reset Filter
                        </a>
                    @endif
                </div>

                <a href="{{ route('admin.naik-kelas.index') }}"
                    class="block text-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    Kembali
                </a>
            </div>

            <!-- Panel Kanan: Daftar Siswa -->
            <div class="lg:col-span-3">
                @if ($siswas->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="mt-3 text-gray-400">Tidak ada siswa ditemukan.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.naik-kelas.per-siswa.store') }}" x-data="{ selectAll: false, showModal: false }"
                        x-ref="formPerSiswa">
                        @csrf

                        @if (request('filter_kelas'))
                            <input type="hidden" name="filter_kelas" value="{{ request('filter_kelas') }}">
                        @endif
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Header -->
                            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900">
                                    Daftar Siswa
                                    <span
                                        class="ml-2 text-sm font-normal text-gray-400">({{ $siswas->flatten()->count() }})</span>
                                </h3>
                                <label class="flex items-center gap-2 text-sm text-gray-600">
                                    <input type="checkbox" x-model="selectAll"
                                        @change="$refs.siswaList.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = selectAll)"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span>Pilih Semua</span>
                                </label>
                            </div>

                            <!-- List -->
                            <div class="max-h-[500px] overflow-y-auto" x-ref="siswaList">
                                @foreach ($siswas as $namaKelas => $listSiswa)
                                    <div>
                                        <div class="px-5 py-2 bg-gray-50 border-b border-gray-200 sticky top-0">
                                            <span
                                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $namaKelas }}</span>
                                        </div>
                                        <div class="divide-y divide-gray-50">
                                            @foreach ($listSiswa as $siswa)
                                                <label
                                                    class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-50 cursor-pointer">
                                                    <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}"
                                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                        @change="
                                               const boxes = $refs.siswaList.querySelectorAll('input[type=checkbox]');
                                               selectAll = boxes.length > 0 && Array.from(boxes).every(cb => cb.checked);
                                           ">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $siswa->nama }}</p>
                                                        <p class="text-xs text-gray-500">NIS: {{ $siswa->nis }}</p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer -->
                            <div
                                class="px-5 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-start sm:items-end gap-4">
                                <div class="flex-1 w-full sm:w-auto">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pindah Ke</label>
                                    <select name="kelas_tujuan_id" required
                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Pilih Kelas Tujuan --</option>
                                        @foreach ($kelasTujuan->groupBy('tahun_ajaran') as $ta => $items)
                                            <optgroup label="Tahun Ajaran {{ $ta }}">
                                                @foreach ($items as $kt)
                                                    <option value="{{ $kt->id }}">[{{ $kt->tingkat }}]
                                                        {{ $kt->nama }} — {{ $kt->jurusan->nama }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" @click="showModal = true"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shrink-0">
                                    Proses Pindah
                                </button>
                            </div>
                        </div>

                        {{-- Popup Konfirmasi --}}
                        <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50">
                            <div class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
                            <div class="fixed inset-0 flex items-center justify-center p-4">
                                <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6" @click.stop>
                                    <div
                                        class="flex items-center justify-center w-12 h-12 mx-auto rounded-full bg-amber-100 mb-4">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 text-center">Konfirmasi Pemindahan</h3>
                                    <p class="mt-2 text-sm text-gray-500 text-center">Siswa terpilih akan dipindahkan ke
                                        kelas tujuan. Lanjutkan proses?</p>
                                    <div class="mt-6 flex gap-3">
                                        <button type="button" @click="showModal = false"
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            Batal
                                        </button>
                                        <button type="button" @click="$refs.formPerSiswa.submit()"
                                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                            Ya, Proses
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
