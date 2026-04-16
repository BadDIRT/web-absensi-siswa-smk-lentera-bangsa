@extends('layouts.app')

@section('title', $jurusan ? 'Edit Jurusan — SMK Lentera Bangsa' : 'Tambah Jurusan — SMK Lentera Bangsa')
@section('page-title', $jurusan ? 'Edit Jurusan' : 'Tambah Jurusan')

@section('content')
    <div class="mx-auto max-w-2xl animate-fade-in">

        <nav class="mb-6 flex items-center gap-1.5 text-xs text-gray-400">
            <a href="{{ route('admin.jurusan.index') }}" class="hover:text-gray-600 transition-colors">Data Jurusan</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-600">{{ $jurusan ? 'Edit' : 'Tambah' }}</span>
        </nav>

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <form method="POST"
                action="{{ $jurusan ? route('admin.jurusan.update', $jurusan) : route('admin.jurusan.store') }}">
                @if ($jurusan)
                    @method('PUT')
                @endif
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="kode" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Kode Jurusan <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="kode" name="kode" value="{{ old('kode', $jurusan?->kode) }}"
                            required maxlength="10" placeholder="Contoh: RPL"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('kode')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Nama Jurusan <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $jurusan?->nama) }}"
                            required maxlength="100" placeholder="Contoh: Rekayasa Perangkat Lunak"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-xs font-medium text-gray-600 mb-1.5">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 resize-none">{{ old('keterangan', $jurusan?->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <a href="{{ route('admin.jurusan.index') }}"
                        class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                        @if ($jurusan)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Simpan Perubahan
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Jurusan
                        @endif
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
