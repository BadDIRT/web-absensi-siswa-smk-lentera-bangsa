@extends('layouts.app')

@section('title', 'Data Kelas — SMK Lentera Bangsa')
@section('page-title', 'Data Kelas')

@section('content')
    <div class="animate-fade-in">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                    <x-icon name="building" class="w-5 h-5" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Data Kelas</p>
                    <p class="text-xs text-gray-400">{{ $kelases->total() }} data ditemukan</p>
                </div>
            </div>
            <a href="{{ route('admin.kelas.create') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98] sm:w-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Kelas
            </a>
        </div>

        {{-- Filter --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas..."
                class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 w-full max-w-xs">
            <select name="jurusan_id" onchange="this.form.submit()"
                class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <option value="">Semua Jurusan</option>
                @foreach ($jurusans as $j)
                    <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                        {{ $j->nama }}</option>
                @endforeach
            </select>
            <select name="tingkat" onchange="this.form.submit()"
                class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <option value="">Semua Tingkat</option>
                <option value="10" {{ request('tingkat') === '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('tingkat') === '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('tingkat') === '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>
            @if (request('search') || request('jurusan_id') || request('tingkat'))
                <a href="{{ route('admin.kelas.index') }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 transition-colors">Reset</a>
            @endif
        </form>

        {{-- Tabel --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama Kelas</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jurusan</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Tingkat</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Tahun Ajaran</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Siswa
                            Aktif</th>
                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-right">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kelases as $kelas)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.kelas.show', $kelas) }}"
                                    class="font-medium text-gray-800 hover:text-brand-600 transition-colors">
                                    {{ $kelas->nama }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $kelas->jurusan->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">Kelas {{ $kelas->tingkat }}</td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $kelas->tahun_ajaran }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span
                                    class="inline-flex items-center justify-center h-7 min-w-[28px] rounded-full bg-brand-50 px-2 text-xs font-semibold text-brand-700">
                                    {{ $kelas->siswa_aktif_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-0.5">
                                    <a href="{{ route('admin.kelas.show', $kelas) }}"
                                        class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-brand-600 transition-colors"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.kelas.edit', $kelas) }}"
                                        class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        onclick="openDeleteModal('{{ $kelas->nama }}', '{{ route('admin.kelas.destroy', $kelas) }}', '{{ csrf_token() }}')"
                                        class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                                        <x-icon name="building" class="w-5 h-5" />
                                    </div>
                                    <p class="text-sm text-gray-400">Belum ada data kelas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $kelases->withQueryString()->links() }}

    </div>

    {{-- ── Modal Hapus ── --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
        style="background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-500 mb-4">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900">Hapus Kelas</h3>
            <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">
                Yakin ingin menghapus <strong id="deleteItemName" class="text-gray-800"></strong>? Semua data siswa
                di dalam kelas ini akan ikut terhapus.
            </p>
            <form id="deleteForm" method="POST" class="mt-6 flex items-center justify-end gap-2">
                <input type="hidden" name="_token" id="deleteToken">
                <input type="hidden" name="_method" value="DELETE">
                <button type="button" onclick="closeDeleteModal()"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-red-500 active:scale-[0.98]">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openDeleteModal(name, url, token) {
                document.getElementById('deleteItemName').textContent = name;
                document.getElementById('deleteForm').action = url;
                document.getElementById('deleteToken').value = token;
                const modal = document.getElementById('deleteModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeDeleteModal() {
                const modal = document.getElementById('deleteModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) closeDeleteModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeDeleteModal();
            });
        </script>
    @endpush
@endsection
