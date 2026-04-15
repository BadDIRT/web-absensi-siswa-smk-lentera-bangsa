@extends('layouts.app')

@section('title', $siswa ?? null ? 'Edit Siswa' : 'Tambah Siswa')
@section('page-title', $siswa ?? null ? 'Edit Siswa' : 'Tambah Siswa')

@section('content')
    <div class="mx-auto max-w-3xl animate-fade-in">

        <x-page-header :title="$siswa ?? null ? 'Edit Siswa' : 'Tambah Siswa'" :back="route('admin.siswa.index')" />

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <form method="POST"
                action="{{ $siswa ?? null ? route('admin.siswa.update', $siswa) : route('admin.siswa.store') }}">
                @if ($siswa ?? null)
                    @method('PUT')
                @endif
                @csrf

                <div class="space-y-5">
                    {{-- Baris 1: Kelas + NIS --}}
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="kelas_id" class="mb-1.5 block text-sm font-medium text-gray-700">Kelas</label>
                            <select id="kelas_id" name="kelas_id" required
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelases as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kelas_id', $siswa->kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }} — {{ $k->jurusan->nama }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nis" class="mb-1.5 block text-sm font-medium text-gray-700">NIS</label>
                            <input type="text" id="nis" name="nis" value="{{ old('nis', $siswa->nis ?? '') }}"
                                required maxlength="20" placeholder="Nomor Induk Siswa"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 font-mono">
                            @error('nis')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- NIPD --}}
                    <div>
                        <label for="nipd" class="mb-1.5 block text-sm font-medium text-gray-700">NIPD</label>
                        <input type="text" id="nipd" name="nipd" value="{{ old('nipd', $siswa->nipd ?? '') }}"
                            required maxlength="20" placeholder="Nomor Induk Pokok Digital"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 font-mono">
                        <p class="mt-1 text-xs text-gray-400">Digunakan sebagai isi barcode CODABAR. Contoh: 242510181</p>
                        @error('nipd')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Baris 2: Nama + JK --}}
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="nama" class="mb-1.5 block text-sm font-medium text-gray-700">Nama
                                Lengkap</label>
                            <input type="text" id="nama" name="nama"
                                value="{{ old('nama', $siswa->nama ?? '') }}" required maxlength="100"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            @error('nama')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="jenis_kelamin" class="mb-1.5 block text-sm font-medium text-gray-700">Jenis
                                Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Pilih</option>
                                <option value="L"
                                    {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="P"
                                    {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 3: Tempat + Tanggal Lahir --}}
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="tempat_lahir" class="mb-1.5 block text-sm font-medium text-gray-700">Tempat
                                Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $siswa->tempat_lahir ?? '') }}" maxlength="100"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                        <div>
                            <label for="tanggal_lahir" class="mb-1.5 block text-sm font-medium text-gray-700">Tanggal
                                Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d') ?? '') }}"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="mb-1.5 block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
                    </div>

                    {{-- Baris 4: Telepon + Status --}}
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="no_telepon" class="mb-1.5 block text-sm font-medium text-gray-700">No.
                                Telepon</label>
                            <input type="text" id="no_telepon" name="no_telepon"
                                value="{{ old('no_telepon', $siswa->no_telepon ?? '') }}" maxlength="15"
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>
                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status" required
                                class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                                <option value="aktif"
                                    {{ old('status', $siswa->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="tidak_aktif"
                                    {{ old('status', $siswa->status ?? '') === 'tidak_aktif' ? 'selected' : '' }}>Tidak
                                    Aktif</option>
                                <option value="pindah"
                                    {{ old('status', $siswa->status ?? '') === 'pindah' ? 'selected' : '' }}>Pindah
                                </option>
                                <option value="lulus"
                                    {{ old('status', $siswa->status ?? '') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                            </select>
                        </div>
                    </div>

                    {{-- Buat Akun (hanya saat create) --}}
                    @if (!$siswa)
                        <div class="rounded-lg border border-blue-100 bg-blue-50/50 p-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="buat_akun" value="1"
                                    class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                <div>
                                    <span class="text-sm font-medium text-blue-900">Buatkan akun login</span>
                                    <p class="text-xs text-blue-700/70">Username = NIS, Password = NIS. Akun bisa diubah di
                                        Pengaturan.</p>
                                </div>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                        {{ $siswa ?? null ? 'Simpan Perubahan' : 'Tambah Siswa' }}
                    </button>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection
