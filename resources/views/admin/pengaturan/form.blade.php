@extends('layouts.app')

@section('title', $user ? 'Edit Pengguna — SMK Lentera Bangsa' : 'Tambah Pengguna — SMK Lentera Bangsa')
@section('page-title', $user ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
    <div class="mx-auto max-w-2xl animate-fade-in">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-1.5 text-xs text-gray-400">
            <a href="{{ route('admin.pengaturan.index') }}" class="hover:text-gray-600 transition-colors">Kelola Pengguna</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-600">{{ $user ? 'Edit' : 'Tambah' }}</span>
        </nav>

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <form method="POST"
                action="{{ $user ? route('admin.pengaturan.update', $user) : route('admin.pengaturan.store') }}">
                @if ($user)
                    @method('PUT')
                @endif
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Nama <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user?->name) }}" required
                            maxlength="100" placeholder="Masukkan nama lengkap"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Username <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user?->username) }}"
                            required maxlength="50" placeholder="Masukkan username"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('username')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user?->email) }}"
                            required maxlength="100" placeholder="contoh@email.com"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Password
                            @if (!$user)
                                <span class="text-red-400">*</span>
                            @else
                                <span class="text-gray-400">(kosongkan jika tidak diubah)</span>
                            @endif
                        </label>
                        <input type="password" id="password" name="password" {{ !$user ? 'required' : '' }} minlength="6"
                            placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter' }}"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Konfirmasi Password
                            @if (!$user)
                                <span class="text-red-400">*</span>
                            @endif
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            {{ !$user ? 'required' : '' }} placeholder="Ulangi password"
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-medium text-gray-600 mb-1.5">
                            Role <span class="text-red-400">*</span>
                        </label>
                        <select id="role" name="role" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="" disabled {{ !$user && !old('role') ? 'selected' : '' }}>Pilih Role
                            </option>
                            @foreach (\App\Models\User::roleLabels() as $val => $label)
                                <option value="{{ $val }}"
                                    {{ (old('role') ?: $user?->role) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <a href="{{ route('admin.pengaturan.index') }}"
                        class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                        @if ($user)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Simpan Perubahan
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Pengguna
                        @endif
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
