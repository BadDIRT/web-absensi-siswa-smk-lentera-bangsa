@extends('layouts.app')

@section('title', $kelas ?? null ? 'Edit Kelas' : 'Tambah Kelas')
@section('page-title', $kelas ?? null ? 'Edit Kelas' : 'Tambah Kelas')

@section('content')
    <div class="mx-auto max-w-2xl animate-fade-in">

        <x-page-header :title="$kelas ?? null ? 'Edit Kelas' : 'Tambah Kelas'" :back="route('admin.kelas.index')" />

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <form method="POST"
                action="{{ $kelas ?? null ? route('admin.kelas.update', $kelas) : route('admin.kelas.store') }}">
                @if ($kelas ?? null)
                    @method('PUT')
                @endif
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="jurusan_id" class="mb-1.5 block text-sm font-medium text-gray-700">Jurusan</label>
                        <select id="jurusan_id" name="jurusan_id" required
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="">Pilih Jurusan</option>
                            @foreach ($jurusans as $j)
                                <option value="{{ $j->id }}"
                                    {{ old('jurusan_id', $kelas->jurusan_id ?? '') == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }} ({{ $j->kode }})</option>
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="mb-1.5 block text-sm font-medium text-gray-700">Nama Kelas</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $kelas->nama ?? '') }}"
                            required maxlength="50" placeholder="Contoh: RPL 1 / TKJ 2 / TKRO 3 / TBSM 4"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tingkat" class="mb-1.5 block text-sm font-medium text-gray-700">Tingkat</label>
                        <select id="tingkat" name="tingkat" required
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="">Pilih Tingkat</option>
                            <option value="10" {{ old('tingkat', $kelas->tingkat ?? '') == 10 ? 'selected' : '' }}>Kelas
                                10</option>
                            <option value="11" {{ old('tingkat', $kelas->tingkat ?? '') == 11 ? 'selected' : '' }}>Kelas
                                11</option>
                            <option value="12" {{ old('tingkat', $kelas->tingkat ?? '') == 12 ? 'selected' : '' }}>
                                Kelas 12</option>
                        </select>
                        @error('tingkat')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun_ajaran" class="mb-1.5 block text-sm font-medium text-gray-700">Tahun
                            Ajaran</label>
                        <input type="text" id="tahun_ajaran" name="tahun_ajaran"
                            value="{{ old('tahun_ajaran', $kelas->tahun_ajaran ?? '') }}" required maxlength="9"
                            placeholder="2024/2025"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('tahun_ajaran')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                        {{ $kelas ?? null ? 'Simpan Perubahan' : 'Tambah Kelas' }}
                    </button>
                    <a href="{{ route('admin.kelas.index') }}"
                        class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection
