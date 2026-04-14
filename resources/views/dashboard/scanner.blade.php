@extends('layouts.app')

@section('title', 'Scan Absensi — SMK Lentera Bangsa')
@section('page-title', 'Scan Absensi')

@section('head')
    <style>
        [x-cloak] {
            display: none !important;
        }

        {{-- Animasi scanning line --}} @keyframes scan-line {
            0% {
                top: 0%;
            }

            50% {
                top: calc(100% - 2px);
            }

            100% {
                top: 0%;
            }
        }

        .scan-line-anim {
            animation: scan-line 2.5s ease-in-out infinite;
        }
    </style>
@endsection

@section('content')
    <div class="mx-auto max-w-4xl space-y-6 animate-fade-in" x-data="{
        scanning: false,
        lastScan: null,
        scanCount: 0,
    }">

        {{-- ── Status Bar ── --}}
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-5 py-3">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="flex h-3 w-3" :class="scanning ? 'bg-green-500' : 'bg-gray-300'">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"
                            :class="scanning ? 'bg-green-400' : 'bg-gray-300'" x-show="scanning"></span>
                    </span>
                </div>
                <span class="text-sm font-medium" :class="scanning ? 'text-green-700' : 'text-gray-500'"
                    x-text="scanning ? 'Sedang memindai...' : 'Siap memindai'"></span>
            </div>
            <div class="text-xs text-gray-400">
                <span x-text="'Scan hari ini: ' + scanCount"></span>
            </div>
        </div>

        {{-- ── Area Kamera / Scanner ── --}}
        <div class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-300 bg-gray-900 transition-colors"
            :class="scanning ? 'border-brand-500' : 'border-gray-300'">

            {{-- Viewfinder kamera — placeholder --}}
            <div class="flex flex-col items-center justify-center py-24 sm:py-32">
                <div class="relative mb-6">
                    {{-- Ikon kamera besar --}}
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/10 text-gray-500">
                        <x-icon name="camera" class="w-10 h-10" />
                    </div>
                </div>

                <p class="text-sm font-medium text-gray-400">Area Kamera</p>
                <p class="mt-1 max-w-xs text-center text-xs text-gray-600">
                    Arahkan kamera ke barcode CODABAR yang dimiliki siswa untuk melakukan absensi.
                </p>

                {{-- Tombol mulai scan (placeholder) --}}
                <button type="button" @click="scanning = !scanning; scanCount += scanning ? 0 : 0;"
                    class="mt-6 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition-all hover:bg-brand-500 active:scale-95">
                    <x-icon name="camera" class="w-4 h-4" />
                    <span x-text="scanning ? 'Berhenti Scan' : 'Mulai Scan'"></span>
                </button>
            </div>

            {{-- Garis scan animasi --}}
            <div x-show="scanning" x-transition
                class="absolute left-4 right-4 h-0.5 bg-gradient-to-r from-transparent via-brand-400 to-transparent scan-line-anim">
            </div>

            {{-- Corner brackets viewfinder --}}
            <div class="absolute top-4 left-4 h-8 w-8 border-l-2 border-t-2 border-white/20 rounded-tl-lg"
                :class="scanning && 'border-brand-400'"></div>
            <div class="absolute top-4 right-4 h-8 w-8 border-r-2 border-t-2 border-white/20 rounded-tr-lg"
                :class="scanning && 'border-brand-400'"></div>
            <div class="absolute bottom-4 left-4 h-8 w-8 border-l-2 border-b-2 border-white/20 rounded-bl-lg"
                :class="scanning && 'border-brand-400'"></div>
            <div class="absolute bottom-4 right-4 h-8 w-8 border-r-2 border-b-2 border-white/20 rounded-br-lg"
                :class="scanning && 'border-brand-400'"></div>
        </div>

        {{-- ── Hasil Scan Terakhir ── --}}
        <div x-show="lastScan" x-transition class="rounded-xl border border-green-200 bg-green-50 p-5">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-800">Absensi Berhasil Dicatat</p>
                    <p class="mt-1 text-sm text-green-700">
                        <span x-text="lastScan?.name ?? '—'"></span>
                        <span class="mx-1.5 text-green-400">|</span>
                        <span x-text="lastScan?.nis ?? '—'"></span>
                    </p>
                    <p class="mt-0.5 text-xs text-green-600/70" x-text="lastScan?.time ?? ''"></p>
                </div>
            </div>
        </div>

        {{-- ── Riwayat Scan Hari Ini ── --}}
        <div class="rounded-xl border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-800">Riwayat Scan Hari Ini</h3>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500"
                    x-text="scanCount + ' data'"></span>
            </div>
            <div class="divide-y divide-gray-50">
                @if ($recentScans->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-300 mb-3">
                            <x-icon name="barcode" class="w-5 h-5" />
                        </div>
                        <p class="text-sm text-gray-400">Belum ada data scan</p>
                        <p class="mt-0.5 text-xs text-gray-300">Mulai scan untuk melihat riwayat.</p>
                    </div>
                @else
                    @foreach ($recentScans as $scan)
                        {{-- Placeholder list item --}}
                    @endforeach
                @endif
            </div>
        </div>

    </div>
@endsection
