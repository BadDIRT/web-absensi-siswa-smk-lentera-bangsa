@extends('layouts.app')

@section('title', 'Profil Saya — SMK Lentera Bangsa')
@section('page-title', 'Profil Saya')

@section('content')
    <div class="mx-auto max-w-2xl animate-fade-in">

        <x-page-header title="Profil Saya" />

        @if (!$siswa)
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 text-center">
                <p class="text-sm font-medium text-amber-800">Data siswa belum terhubung dengan akun Anda.</p>
                <p class="mt-1 text-xs text-amber-600">Silakan hubungi administrator.</p>
            </div>
        @else
            {{-- Info Utama --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                    {{-- Avatar --}}
                    <div
                        class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-brand-100 text-2xl font-bold text-brand-700">
                        {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-lg font-bold text-gray-900">{{ $siswa->nama }}</h3>
                        <p class="text-sm text-gray-500">NIS: <span class="font-mono">{{ $siswa->nis }}</span></p>
                        <p class="text-sm text-gray-500">{{ $siswa->kelas->nama }} — {{ $siswa->kelas->jurusan->nama }}</p>
                        <div
                            class="mt-2 inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-50 text-green-700">
                            {{ $siswa->statusLabel() }}
                        </div>
                    </div>
                </div>

                {{-- Detail Info --}}
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-medium text-gray-400">Jenis Kelamin</p>
                        <p class="mt-0.5 text-sm text-gray-700">
                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400">Tempat, Tanggal Lahir</p>
                        <p class="mt-0.5 text-sm text-gray-700">
                            {{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' : '' }}{{ $siswa->tanggal_lahir?->format('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400">No. Telepon</p>
                        <p class="mt-0.5 text-sm text-gray-700">{{ $siswa->no_telepon ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400">Kode Barcode</p>
                        <p class="mt-0.5 text-sm font-mono text-gray-700">{{ $siswa->no_barcode }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-medium text-gray-400">Alamat</p>
                        <p class="mt-0.5 text-sm text-gray-700">{{ $siswa->alamat ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Edit Alamat & Telepon --}}
            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6">
                <h4 class="mb-4 text-sm font-semibold text-gray-800">Edit Kontak</h4>
                <form method="POST" action="{{ route('siswa.profil.update') }}">
                    @method('PUT')
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="no_telepon" class="mb-1.5 block text-sm font-medium text-gray-700">No.
                                Telepon</label>
                            <input type="text" id="no_telepon" name="no_telepon"
                                value="{{ old('no_telepon', $siswa->no_telepon ?? '') }}" maxlength="15"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            @error('no_telepon')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="alamat" class="mb-1.5 block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5 border-t border-gray-100 pt-4">
                        <button type="submit"
                            class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
@endsection
