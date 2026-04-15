@extends('layouts.app')

@section('title', $jurusan ? 'Edit Jurusan' : 'Tambah Jurusan')
@section('page-title', $jurusan ? 'Edit Jurusan' : 'Tambah Jurusan')

@section('content')
    <div class="mx-auto max-w-2xl animate-fade-in">

        <x-page-header :title="$jurusan ? 'Edit Jurusan' : 'Tambah Jurusan'" :back="route('admin.jurusan.index')" />

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <form method="POST"
                action="{{ $jurusan ? route('admin.jurusan.update', $jurusan) : route('admin.jurusan.store') }}">
                @if ($jurusan)
                    @method('PUT')
                @endif
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="kode" class="mb-1.5 block text-sm font-medium text-gray-700">Kode Jurusan</label>
                        <input type="text" id="kode" name="kode" value="{{ old('kode', $jurusan->kode ?? '') }}"
                            required maxlength="10" placeholder="Contoh: RPL"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('kode')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="mb-1.5 block text-sm font-medium text-gray-700">Nama Jurusan</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $jurusan->nama ?? '') }}"
                            required maxlength="100" placeholder="Contoh: Rekayasa Perangkat Lunak"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="mb-1.5 block text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Opsional..."
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">{{ old('keterangan', $jurusan->keterangan ?? '') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                        {{ $jurusan ? 'Simpan Perubahan' : 'Tambah Jurusan' }}
                    </button>
                    <a href="{{ route('admin.jurusan.index') }}"
                        class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection
