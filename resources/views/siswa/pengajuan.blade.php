@extends('layouts.app')

@section('title', 'Pengajuan Izin/Sakit — SMK Lentera Bangsa')
@section('page-title', 'Pengajuan Izin/Sakit')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6 animate-fade-in">

        {{-- Back Link --}}
        <div>
            <a href="{{ route('dashboard.siswa') }}" class="text-sm text-brand-600 hover:text-brand-700 transition-colors">
                ← Kembali ke Dashboard
            </a>
        </div>

        {{-- DIPERBAIKI: Menggunakan variabel $bisaAjukan dari Controller --}}
        @if (!$bisaAjukan)
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 text-center">
                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-500 mb-3">
                    <x-icon name="exclamation-circle" class="w-6 h-6" />
                </div>
                <h3 class="text-lg font-semibold text-amber-800">Tidak Bisa Mengajukan</h3>
                <p class="mt-1 text-sm text-amber-600">Anda sudah memiliki catatan absensi atau pengajuan untuk hari ini.</p>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 bg-white p-6" x-data="{ jenis: 'izin' }">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Form Pengajuan</h3>

                <form action="{{ route('siswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Pilihan Jenis (Izin / Sakit) --}}
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Jenis Pengajuan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="relative flex cursor-pointer items-center justify-center rounded-xl border-2 p-4 transition-all
                                {{ old('jenis') === 'izin' ? 'border-brand-500 bg-brand-50 ring-1 ring-brand-500/20' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="jenis" value="izin" class="sr-only"
                                    @if (old('jenis') === 'izin') checked @endif x-model="jenis">
                                <div class="text-center">
                                    <div
                                        class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                        <x-icon name="document-text" class="w-5 h-5" />
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">Izin</span>
                                </div>
                            </label>
                            <label
                                class="relative flex cursor-pointer items-center justify-center rounded-xl border-2 p-4 transition-all
                                {{ old('jenis') === 'sakit' ? 'border-brand-500 bg-brand-50 ring-1 ring-brand-500/20' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="jenis" value="sakit" class="sr-only"
                                    @if (old('jenis') === 'sakit') checked @endif x-model="jenis">
                                <div class="text-center">
                                    <div
                                        class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                        <x-icon name="heart" class="w-5 h-5" />
                                    </div>
                                    <span class="text-sm font-semibold text-gray-800">Sakit</span>
                                </div>
                            </label>
                        </div>
                        @error('jenis')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <label for="keterangan" class="mb-1 block text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3"
                            class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
                            placeholder="Jelaskan alasan Anda...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload Surat (Hanya muncul jika Sakit) --}}
                    <div class="mb-6" x-show="jenis === 'sakit'" x-transition>
                        <label for="foto_surat" class="mb-1 block text-sm font-medium text-gray-700">Foto Surat Keterangan
                            Sakit <span class="text-red-500">*</span></label>
                        <input type="file" id="foto_surat" name="foto_surat" accept="image/jpeg,image/png,image/jpg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        <p class="mt-1.5 text-xs text-gray-400">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                        @error('foto_surat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('dashboard.siswa') }}"
                            class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.97]">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
@endsection
