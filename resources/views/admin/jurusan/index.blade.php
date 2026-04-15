@extends('layouts.app')

@section('title', 'Data Jurusan — SMK Lentera Bangsa')
@section('page-title', 'Data Jurusan')

@section('content')
    <div class="animate-fade-in">

        <x-page-header title="Data Jurusan" :action='new \Illuminate\Support\HtmlString(
            "<a href=\"" .
                route('admin.jurusan.create') .
                "\" class=\"inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-500 active:scale-[0.98]\">
                    <svg class=\"w-4 h-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 4.5v15m7.5-7.5h-15\" /></svg>
                    Tambah Jurusan
                </a>",
        )' />

        {{-- Search --}}
        <form method="GET" class="mb-4">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode atau nama jurusan..."
                    class="block w-full max-w-sm rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <button type="submit"
                    class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 transition-colors">Cari</button>
            </div>
        </form>

        {{-- Tabel --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Kode</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Nama Jurusan</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Jumlah Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400 text-right">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($jurusans as $jurusan)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3.5">
                                <span
                                    class="inline-flex rounded bg-brand-50 px-2 py-0.5 text-xs font-bold text-brand-700">{{ $jurusan->kode }}</span>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-gray-800">{{ $jurusan->nama }}</td>
                            <td class="px-6 py-3.5 text-gray-500">{{ $jurusan->kelases->count() }} kelas</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.jurusan.edit', $jurusan) }}"
                                        class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.jurusan.destroy', $jurusan) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <p class="text-sm text-gray-400">Belum ada data jurusan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $jurusans->withQueryString()->links() }}

    </div>
@endsection
