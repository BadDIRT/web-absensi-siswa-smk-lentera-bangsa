@extends('layouts.app')

@section('title', $siswa ? 'Edit Siswa — SMK Lentera Bangsa' : 'Tambah Siswa — SMK Lentera Bangsa')
@section('page-title', $siswa ? 'Edit Data Siswa' : 'Tambah Siswa Baru')

@section('content')
    <div class="animate-fade-in max-w-3xl">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-1.5 text-xs text-gray-400">
            <a href="{{ route('admin.siswa.index') }}" class="hover:text-gray-600 transition-colors">Data Siswa</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-600">{{ $siswa ? 'Edit' : 'Tambah' }}</span>
        </nav>

        {{-- Form --}}
        <form method="POST" action="{{ $siswa ? route('admin.siswa.update', $siswa) : route('admin.siswa.store') }}"
            class="space-y-6">
            @csrf
            @if ($siswa)
                @method('PUT')
            @endif

            {{-- Data Utama --}}
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-800">Data Utama</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi dasar dan identitas siswa</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- NIS --}}
                        <div>
                            <label for="nis" class="block text-xs font-medium text-gray-600 mb-1.5">
                                NIS <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="nis" name="nis" value="{{ old('nis', $siswa?->nis) }}"
                                required maxlength="20" placeholder="Contoh: 2024001"
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            @error('nis')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIPD --}}
                        <div>
                            <label for="nipd" class="block text-xs font-medium text-gray-600 mb-1.5">
                                NIPD <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="nipd" name="nipd" value="{{ old('nipd', $siswa?->nipd) }}"
                                required maxlength="20" placeholder="Contoh: 242510181"
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            @error('nipd')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-[11px] text-gray-400">Digunakan untuk generate kode barcode</p>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label for="nama" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $siswa?->nama) }}"
                            required maxlength="100" placeholder="Masukkan nama lengkap"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        {{-- Jenis Kelamin --}}
                        <div>
                            <label for="jenis_kelamin" class="block text-xs font-medium text-gray-600 mb-1.5">
                                Jenis Kelamin <span class="text-red-400">*</span>
                            </label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="" disabled {{ !$siswa && !old('jenis_kelamin') ? 'selected' : '' }}>
                                    Pilih</option>
                                <option value="L"
                                    {{ (old('jenis_kelamin') ?: $siswa?->jenis_kelamin) === 'L' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="P"
                                    {{ (old('jenis_kelamin') ?: $siswa?->jenis_kelamin) === 'P' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kelas --}}
                        <div>
                            <label for="kelas_id" class="block text-xs font-medium text-gray-600 mb-1.5">
                                Kelas <span class="text-red-400">*</span>
                            </label>
                            <select id="kelas_id" name="kelas_id" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="" disabled {{ !$siswa && !old('kelas_id') ? 'selected' : '' }}>Pilih
                                    Kelas</option>
                                @foreach ($kelases as $k)
                                    <option value="{{ $k->id }}"
                                        {{ (old('kelas_id') ?: $siswa?->kelas_id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }} — {{ $k->jurusan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-600 mb-1.5">
                                Status <span class="text-red-400">*</span>
                            </label>
                            <select id="status" name="status" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                @foreach (['aktif' => 'Aktif', 'tidak_aktif' => 'Tidak Aktif', 'pindah' => 'Pindah', 'lulus' => 'Lulus'] as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ (old('status') ?: $siswa?->status) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Tambahan --}}
            <div class="rounded-xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-800">Data Tambahan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi kelahiran dan kontak (opsional)</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- Tempat Lahir --}}
                        <div>
                            <label for="tempat_lahir" class="block text-xs font-medium text-gray-600 mb-1.5">
                                Tempat Lahir
                            </label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $siswa?->tempat_lahir) }}" maxlength="100"
                                placeholder="Contoh: Jakarta"
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="tanggal_lahir" class="block text-xs font-medium text-gray-600 mb-1.5">
                                Tanggal Lahir
                            </label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $siswa?->tanggal_lahir?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block text-xs font-medium text-gray-600 mb-1.5">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 resize-none">{{ old('alamat', $siswa?->alamat) }}</textarea>
                    </div>

                    {{-- No Telepon --}}
                    <div class="sm:max-w-xs">
                        <label for="no_telepon" class="block text-xs font-medium text-gray-600 mb-1.5">
                            No. Telepon
                        </label>
                        <input type="text" id="no_telepon" name="no_telepon"
                            value="{{ old('no_telepon', $siswa?->no_telepon) }}" maxlength="15"
                            placeholder="08xxxxxxxxxx"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('no_telepon')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.siswa.index') }}"
                    class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                    @if ($siswa)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Simpan Perubahan
                    @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Siswa
                    @endif
                </button>
            </div>
        </form>
    </div>
@endsection
