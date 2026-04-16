@extends('layouts.app')

@section('title', 'Pengajuan Izin/Sakit')
@section('page-title', 'Pengajuan Izin / Sakit')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6 animate-fade-in">
        <div>
            <a href="{{ route('dashboard.siswa') }}" class="text-sm text-brand-600 hover:text-brand-700 transition-colors">
                ← Kembali ke Dashboard
            </a>
        </div>

        @if ($sudahAbsen)
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

                <form method="POST" action="{{ route('siswa.pengajuan.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Pilihan Jenis --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pengajuan</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="relative flex cursor-pointer items-center rounded-lg border-2 p-4 transition-colors 
                                {{ old('jenis') === 'izin' ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300' }}"
                                :class="jenis === 'izin' ? 'border-brand-500 bg-brand-50' :
                                    'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="jenis" value="izin" class="sr-only" x-model="jenis"
                                    {{ old('jenis') === 'izin' ? 'checked' : '' }} required>
                                <div class="flex w-full items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                        <x-icon name="document-text" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Izin</p>
                                        <p class="text-xs text-gray-500">Keperluan pribadi/ keluarga</p>
                                    </div>
                                </div>
                            </label>

                            <label
                                class="relative flex cursor-pointer items-center rounded-lg border-2 p-4 transition-colors
                                {{ old('jenis') === 'sakit' ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300' }}"
                                :class="jenis === 'sakit' ? 'border-brand-500 bg-brand-50' :
                                    'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="jenis" value="sakit" class="sr-only" x-model="jenis"
                                    {{ old('jenis') === 'sakit' ? 'checked' : '' }}>
                                <div class="flex w-full items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600">
                                        <x-icon name="heart" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Sakit</p>
                                        <p class="text-xs text-gray-500">Wajib lampirkan foto surat</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-5">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" required
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-brand-500 focus:border-brand-500"
                            :placeholder="jenis === 'izin' ? 'Contoh: Acara keluarga di luar kota' :
                                'Contoh: Demam tinggi, sedang rawat jalan'">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload Foto (Hanya Muncul jika Sakit) --}}
                    <div class="mb-6" x-show="jenis === 'sakit'" x-cloak x-transition>
                        <label for="foto_surat" class="block text-sm font-medium text-gray-700 mb-1">
                            Foto Surat Keterangan <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="foto_surat" name="foto_surat" accept="image/jpeg,image/png,image/jpg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, JPEG. Maksimal 2MB.</p>
                        @error('foto_surat')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('dashboard.siswa') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-brand-600 text-white rounded-lg text-sm font-medium hover:bg-brand-700 transition-colors">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection
