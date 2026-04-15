@extends('layouts.app')

@section('title', $user ?? null ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('page-title', $user ?? null ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
    <div class="mx-auto max-w-2xl animate-fade-in">

        <x-page-header :title="$user ?? null ? 'Edit Pengguna' : 'Tambah Pengguna'" :back="route('admin.pengaturan.index')" />

        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <form method="POST"
                action="{{ $user ?? null ? route('admin.pengaturan.update', $user) : route('admin.pengaturan.store') }}">
                @if ($user ?? null)
                    @method('PUT')
                @endif
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                            required maxlength="100"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="username" name="username"
                            value="{{ old('username', $user->username ?? '') }}" required maxlength="50"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 font-mono">
                        @error('username')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="mb-1.5 block text-sm font-medium text-gray-700">Role</label>
                        <select id="role" name="role" required
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="administrator"
                                {{ old('role', $user->role ?? '') === 'administrator' ? 'selected' : '' }}>Administrator
                            </option>
                            <option value="scanner" {{ old('role', $user->role ?? '') === 'scanner' ? 'selected' : '' }}>
                                Scanner</option>
                            <option value="siswa" {{ old('role', $user->role ?? '') === 'siswa' ? 'selected' : '' }}>Siswa
                            </option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Password
                            {{ $user ?? null ? '<span class="font-normal text-gray-400">(kosongkan jika tidak diubah)</span>' : '' }}
                        </label>
                        <input type="password" id="password" name="password" {{ $user ?? null ? '' : 'required' }}
                            minlength="6"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">Konfirmasi
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            {{ $user ?? null ? '' : 'required' }}
                            class="block w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        @error('password_confirmation')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]">
                        {{ $user ?? null ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                    </button>
                    <a href="{{ route('admin.pengaturan.index') }}"
                        class="rounded-lg px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection
