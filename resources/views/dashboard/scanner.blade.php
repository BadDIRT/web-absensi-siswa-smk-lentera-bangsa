@extends('layouts.app')

@section('title', 'Scan Absensi — SMK Lentera Bangsa')
@section('page-title', 'Scan Absensi')

@section('head')
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* ── Animasi ── */
        @keyframes scan-line {
            0% {
                top: 10%;
            }

            50% {
                top: 85%;
            }

            100% {
                top: 10%;
            }
        }

        .scan-line-anim {
            animation: scan-line 2.5s ease-in-out infinite;
        }

        @keyframes notif-in {
            from {
                opacity: 0;
                transform: translateX(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        .notif-enter {
            animation: notif-in 0.35s ease-out both;
        }

        @keyframes flash-ok {
            0% {
                box-shadow: inset 0 0 0 3px rgba(34, 197, 94, 0.9), 0 0 30px rgba(34, 197, 94, 0.3);
            }

            100% {
                box-shadow: inset 0 0 0 3px rgba(34, 197, 94, 0), 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .flash-success {
            animation: flash-ok 0.8s ease-out;
        }

        @keyframes flash-err {
            0% {
                box-shadow: inset 0 0 0 3px rgba(239, 68, 68, 0.9), 0 0 30px rgba(239, 68, 68, 0.3);
            }

            100% {
                box-shadow: inset 0 0 0 3px rgba(239, 68, 68, 0), 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        .flash-error {
            animation: flash-err 0.8s ease-out;
        }

        @keyframes pulse-ring {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(2.2);
                opacity: 0;
            }
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .anim-fade-up {
            animation: fade-up 0.4s ease-out both;
        }

        /* ── Camera (FIX: contain supaya preview & border sejajar) ── */
        /* HAPUS semua CSS untuk #reader, #reader>div, #reader video, #reader canvas */
        /* Lalu ganti dengan ini: */

        #reader {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }

        #reader canvas {
            display: none !important;
        }

        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
        }

        .camera-wrapper {
            width: 100%;
            aspect-ratio: 3/4;
            background: #000;
        }

        .camera-error-bg {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        /* ── Border gradient saat scanning ── */
        .border-scan {
            border-image: linear-gradient(135deg, #6366f1, #8b5cf6, #a78bfa) 1;
        }
    </style>
@endsection

@section('content')
    <div class="mx-auto max-w-2xl space-y-4 animate-fade-in" x-data="scanApp()" x-init="init()">

        {{-- Notifications --}}
        <div class="fixed top-4 right-4 z-[100] flex w-full max-w-sm flex-col gap-2 px-4 sm:px-0" x-cloak>
            <template x-for="n in notifications" :key="n.id">
                <div class="notif-enter rounded-xl border px-4 py-3 shadow-xl text-sm font-medium cursor-pointer backdrop-blur-md"
                    @click="notifications = notifications.filter(i => i.id !== n.id)"
                    :class="{
                        'border-green-200/60 bg-green-50/90 text-green-800': n.type === 'success',
                        'border-amber-200/60 bg-amber-50/90 text-amber-800': n.type === 'warning',
                        'border-red-200/60 bg-red-50/90 text-red-800': n.type === 'error',
                        'border-indigo-200/60 bg-indigo-50/90 text-indigo-800': n.type === 'info',
                    }">
                    <div class="flex items-start gap-2.5">
                        <span class="mt-0.5 shrink-0" x-html="n.icon"></span>
                        <div class="flex-1 min-w-0">
                            <p x-text="n.message" class="leading-snug"></p>
                            <p x-show="n.detail" x-text="n.detail" class="mt-0.5 text-xs opacity-70 truncate"></p>
                        </div>
                        <svg class="w-4 h-4 shrink-0 opacity-30 hover:opacity-100 transition-opacity mt-0.5" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </template>
        </div>

        {{-- Status Bar --}}
        <div class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white px-5 py-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center w-3 h-3">
                    <span class="block w-2.5 h-2.5 rounded-full transition-colors duration-300"
                        :class="cameraError ? 'bg-red-500' : (scanning ? 'bg-emerald-500' : 'bg-gray-300')"></span>
                    <span class="absolute block w-2.5 h-2.5 rounded-full transition-colors duration-300"
                        :class="cameraError ? 'bg-red-400' : (scanning ? 'bg-emerald-400' : 'bg-gray-300')"
                        x-show="scanning || cameraError"
                        :style="!cameraError && scanning ? 'animation: pulse-ring 2s ease-in-out infinite' : ''"></span>
                </div>
                <span class="text-[13px] font-medium transition-colors duration-300"
                    :class="cameraError ? 'text-red-600' : (scanning ? 'text-emerald-700' : 'text-gray-400')"
                    x-text="cameraError ? 'Kamera bermasalah' : (scanning ? (processing ? 'Memproses...' : 'Sedang memindai...') : 'Siap memindai')"></span>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                </svg>
                <span x-text="scanCount + ' hari ini'"></span>
            </div>
        </div>

        {{-- Camera Area --}}
        <div id="camera-container" class="camera-wrapper relative overflow-hidden rounded-2xl transition-all duration-300"
            :class="cameraError ? 'border-2 border-red-500/60 camera-error-bg' : (scanning ?
                'border-2 border-indigo-500/60 bg-black' : 'border-2 border-gray-700/80 bg-gray-900')">

            {{-- Placeholder --}}
            <div x-show="!scanning && !cameraError" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="absolute inset-0 flex flex-col items-center justify-center p-8">
                <div
                    class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-white/[0.07] ring-1 ring-white/[0.05] text-gray-500">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-300">Scan Barcode Siswa</p>
                <p class="mt-1.5 max-w-[260px] text-center text-xs text-gray-500 leading-relaxed">Arahkan kamera ke barcode
                    Code 128 yang ada di kartu siswa</p>
                <div class="mt-6 grid grid-cols-2 gap-2 w-full max-w-[260px]">
                    <div class="rounded-lg bg-white/[0.04] px-3 py-2.5 text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Jarak</p>
                        <p class="text-xs text-gray-400">15–25 cm</p>
                    </div>
                    <div class="rounded-lg bg-white/[0.04] px-3 py-2.5 text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Posisi</p>
                        <p class="text-xs text-gray-400">Tegak lurus</p>
                    </div>
                    <div class="rounded-lg bg-white/[0.04] px-3 py-2.5 text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Cahaya</p>
                        <p class="text-xs text-gray-400">Pakai flash</p>
                    </div>
                    <div class="rounded-lg bg-white/[0.04] px-3 py-2.5 text-center">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-0.5">Gerak</p>
                        <p class="text-xs text-gray-400">Tahan diam</p>
                    </div>
                </div>
            </div>

            {{-- Error State --}}
            <div x-show="cameraError" x-transition:enter="transition ease-out duration-300"
                class="absolute inset-0 flex flex-col items-center justify-center p-8">
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-500/10 ring-1 ring-red-500/20 text-red-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-red-300" x-text="cameraErrorTitle || 'Gagal Mengakses Kamera'"></p>
                <p class="mt-1 max-w-[260px] text-center text-xs text-gray-500"
                    x-text="cameraErrorMessage || 'Pastikan izin kamera telah diberikan.'"></p>
                <button @click="cameraError = false; document.getElementById('reader').innerHTML = ''"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white/10 px-5 py-2.5 text-xs font-semibold text-white hover:bg-white/15 transition-colors ring-1 ring-white/10">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                    </svg>
                    Coba Lagi
                </button>
            </div>

            {{-- Quagga Reader --}}
            <div id="reader" x-show="scanning && !cameraError" x-cloak></div>

            {{-- Scan Line --}}
            <div x-show="scanning && !processing && !showingResult && !cameraError" x-transition.opacity.duration.200ms
                class="pointer-events-none absolute inset-0 z-10">
                <div class="absolute left-[8%] right-[8%] h-[2px] bg-gradient-to-r from-transparent via-indigo-400 to-transparent scan-line-anim"
                    style="box-shadow: 0 0 12px 2px rgba(99,102,241,0.4);"></div>
            </div>

            {{-- Corner Brackets --}}
            <div x-show="scanning && !cameraError" x-transition.opacity.duration.300ms
                class="pointer-events-none absolute inset-0 z-10">
                <div class="absolute top-4 left-4 w-7 h-7 border-l-2 border-t-2 rounded-tl-md border-indigo-400/80"></div>
                <div class="absolute top-4 right-4 w-7 h-7 border-r-2 border-t-2 rounded-tr-md border-indigo-400/80"></div>
                <div class="absolute bottom-4 left-4 w-7 h-7 border-l-2 border-b-2 rounded-bl-md border-indigo-400/80">
                </div>
                <div class="absolute bottom-4 right-4 w-7 h-7 border-r-2 border-b-2 rounded-br-md border-indigo-400/80">
                </div>
            </div>

            {{-- Result Overlay --}}
            <div x-show="showingResult" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute inset-0 z-20 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div
                    class="bg-white rounded-2xl shadow-2xl p-6 max-w-[280px] w-full mx-4 text-center border border-gray-100/80">
                    <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl"
                        :class="resultData && resultData.type === 'masuk' ?
                            'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100' :
                            'bg-blue-50 text-blue-600 ring-1 ring-blue-100'">
                        <svg x-show="resultData && resultData.type === 'masuk'" class="w-8 h-8" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <svg x-show="!resultData || resultData.type !== 'masuk'" class="w-8 h-8" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800" x-text="resultData ? resultData.title : ''"></h3>
                    <p class="text-sm font-bold text-gray-900 mt-1" x-text="resultData ? resultData.nama : ''"></p>
                    <p class="text-xs text-gray-500 mt-0.5" x-text="resultData ? resultData.kelas : ''"></p>
                    <div class="mt-3 rounded-xl bg-gray-50 px-4 py-2.5 text-xs font-mono text-gray-600 tracking-wide"
                        x-text="resultData ? resultData.time : ''"></div>
                </div>
            </div>
        </div>

        {{-- Control Bar --}}
        <div x-show="scanning && !cameraError" x-transition
            class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white px-5 py-3 shadow-sm">
            <button type="button" @click="toggleFlash()"
                class="flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all"
                :class="flashOn ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-200 shadow-sm' :
                    'bg-gray-50 text-gray-500 hover:bg-gray-100'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                </svg>
                <span x-text="flashOn ? 'Flash ON' : 'Flash'"></span>
            </button>
            <button type="button" @click="toggleScan()"
                class="flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-red-600/20 transition-all hover:bg-red-500 active:scale-[0.97]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                </svg>
                Berhenti
            </button>
        </div>

        {{-- Start Button --}}
        <div x-show="!scanning && !cameraError" x-transition class="flex items-center justify-center pt-2">
            <button type="button" @click="toggleScan()"
                class="group inline-flex items-center gap-3 rounded-2xl bg-gradient-to-b from-indigo-600 to-indigo-700 px-10 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/30 transition-all hover:from-indigo-500 hover:to-indigo-600 hover:shadow-xl hover:shadow-indigo-600/30 active:scale-[0.97]">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                Mulai Scan
            </button>
        </div>

        {{-- Riwayat --}}
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-50 px-5 py-3.5">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="text-[13px] font-semibold text-gray-700">Riwayat Hari Ini</h3>
                </div>
                <a href="{{ route('scanner.riwayat.index') }}"
                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua →</a>
            </div>
            <div id="scan-list" class="divide-y divide-gray-50 max-h-[320px] overflow-y-auto">
                @forelse($recentScans as $absensi)
                    <div class="flex items-center gap-3.5 px-5 py-3 hover:bg-gray-50/70 transition-colors">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xs font-bold text-indigo-600">
                            {{ strtoupper(substr($absensi->siswa->nama, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $absensi->siswa->nama }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $absensi->siswa->nipd ?? $absensi->siswa->nis }}
                                · {{ $absensi->siswa->kelas->nama ?? '-' }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-mono font-medium text-gray-600">{{ $absensi->jam_masuk }}</p>
                            @if ($absensi->jam_pulang)
                                <p class="text-[10px] font-mono text-gray-400 mt-0.5">↗ {{ $absensi->jam_pulang }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-50 text-gray-300 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400">Belum ada data scan</p>
                        <p class="mt-0.5 text-xs text-gray-300">Mulai scan untuk melihat riwayat</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2@1.8.4/dist/quagga.min.js"></script>

        <script>
            const ICONS = {
                success: '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
                warning: '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>',
                error: '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>',
                info: '<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>',
            };

            function scanApp() {
                return {
                    scanning: false,
                    processing: false,
                    flashOn: false,
                    cameraError: false,
                    cameraErrorTitle: '',
                    cameraErrorMessage: '',
                    scanCount: {{ $recentScans->count() ?? 0 }},
                    notifications: [],
                    lastCode: null,
                    videoTrack: null,
                    showingResult: false,
                    resultData: null,
                    resultTimer: null,

                    init() {
                        window.addEventListener('beforeunload', () => this.stopScan());
                    },

                    toggleScan() {
                        if (typeof Quagga === 'undefined') return this.notify('error',
                            'Library gagal dimuat. Refresh halaman.');
                        this.scanning ? this.stopScan() : this.startScan();
                    },

                    startScan() {
                        if (this.scanning) return;
                        this.cameraError = false;
                        this.lastCode = null;
                        this.notify('info', 'Membuka kamera...');

                        Quagga.init({
                            inputStream: {
                                name: "Live",
                                type: "LiveStream",
                                target: document.querySelector('#reader'),
                                constraints: {
                                    facingMode: "environment"
                                }
                            },
                            locator: {
                                patchSize: "medium",
                                halfSample: true
                            },
                            numOfWorkers: navigator.hardwareConcurrency || 4,
                            frequency: 10,
                            decoder: {
                                readers: ["code_128_reader"]
                            },
                            locate: true
                        }, (err) => {
                            if (err) {
                                console.error(err);
                                const errors = {
                                    'NotAllowedError': ['Izin Kamera Ditolak',
                                        'Buka pengaturan browser dan izinkan akses kamera.'
                                    ],
                                    'NotFoundError': ['Kamera Tidak Ditemukan', 'Tidak ada kamera terdeteksi.'],
                                    'NotReadableError': ['Kamera Sedang Digunakan',
                                        'Tutup aplikasi lain yang menggunakan kamera.'
                                    ],
                                };
                                const [title, msg] = errors[err.name] || ['Gagal Mengakses Kamera',
                                    'Pastikan izin kamera diberikan.'
                                ];
                                this.cameraError = true;
                                this.cameraErrorTitle = title;
                                this.cameraErrorMessage = msg;
                                this.notify('error', title, msg);
                                return;
                            }
                            Quagga.start();
                            this.scanning = true;
                            this.notifications = [];
                            // Force contain + samakan aspect ratio container dengan video
                            const fitVideo = () => {
                                const v = document.querySelector('#reader video');
                                const c = document.getElementById('camera-container');
                                if (v && v.videoWidth && v.videoHeight) {
                                    v.style.setProperty('object-fit', 'contain', 'important');
                                    v.style.setProperty('width', '100%', 'important');
                                    v.style.setProperty('height', '100%', 'important');
                                    c.style.aspectRatio = v.videoWidth + ' / ' + v.videoHeight;
                                }
                            };

                            // Coba 2x: video kadang perlu loadedmetadata dulu
                            setTimeout(fitVideo, 300);
                            setTimeout(fitVideo, 1000);
                            Quagga.onDetected((r) => this.onDetected(r));
                        });
                    },

                    stopScan() {
                        if (!this.scanning) return;
                        if (this.flashOn && this.videoTrack) {
                            try {
                                this.videoTrack.applyConstraints({
                                    advanced: [{
                                        torch: false
                                    }]
                                });
                            } catch (e) {}
                            this.flashOn = false;
                        }
                        try {
                            Quagga.stop();
                            Quagga.offDetected();
                        } catch (e) {}
                        document.getElementById('reader').innerHTML = '';
                        document.getElementById('camera-container').style.aspectRatio = '';
                        this.scanning = false;
                        this.lastCode = null;
                        this.videoTrack = null;
                    },

                    onDetected(result) {
                        if (this.processing) return;
                        let code = result.codeResult.code.trim();
                        if (code === this.lastCode) return;
                        this.lastCode = code;
                        console.log("SCAN:", code);
                        setTimeout(() => {
                            this.lastCode = null;
                        }, 3000);
                        this.processScan(code);
                    },

                    async processScan(code) {
                        this.processing = true;
                        try {
                            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                            if (!csrf) throw new Error('No CSRF');
                            const ctrl = new AbortController();
                            const tid = setTimeout(() => ctrl.abort(), 15000);
                            const res = await fetch('{{ route('scanner.process') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: JSON.stringify({
                                    barcode: code
                                }),
                                signal: ctrl.signal
                            });
                            clearTimeout(tid);
                            if (!res.ok) throw new Error('HTTP ' + res.status);
                            const result = await res.json();

                            if (result.success) {
                                this.scanCount++;
                                this.flashBorder(true);
                                this.showResult(result);
                                this.beep(true);
                                const d = result.data || {};
                                const detail = `${d.nama || ''} · ${d.kelas || ''}` + (result.type === 'masuk' ?
                                    ` · ${d.time || ''}` : ` · ${d.jam_masuk || ''} → ${d.jam_pulang || ''}`);
                                this.notify('success',
                                    `✦ ${result.type === 'masuk' ? 'MASUK' : 'PULANG'} — ${result.message || 'Berhasil'}`,
                                    detail);
                                this.addToList(result);
                            } else {
                                this.flashBorder(false);
                                this.beep(false);
                                let detail = '';
                                if (result.data) {
                                    detail = result.data.nama || '';
                                    if (result.data.jam_masuk) detail +=
                                        `${detail ? ' · Masuk ' : 'Masuk '}${result.data.jam_masuk}`;
                                    if (result.data.jam_pulang) detail += ' → Pulang ' + result.data.jam_pulang;
                                }
                                this.notify(result.type === 'duplicate' ? 'warning' : 'error', result.message || 'Gagal',
                                    detail);
                            }
                        } catch (e) {
                            this.notify('error', e.name === 'AbortError' ? 'Server tidak merespon.' :
                                'Gagal mengirim data.');
                        } finally {
                            this.processing = false;
                        }
                    },

                    async toggleFlash() {
                        if (!this.videoTrack) {
                            const video = document.querySelector('#reader video');
                            if (video?.srcObject) this.videoTrack = video.srcObject.getVideoTracks()[0];
                        }
                        if (!this.videoTrack) return this.notify('warning', 'Tidak dapat mengakses lampu flash.');
                        try {
                            if (!this.videoTrack.getCapabilities().torch) return this.notify('warning',
                                'Perangkat tidak mendukung flash.');
                            this.flashOn = !this.flashOn;
                            await this.videoTrack.applyConstraints({
                                advanced: [{
                                    torch: this.flashOn
                                }]
                            });
                        } catch (e) {
                            this.notify('error', 'Gagal mengubah lampu flash.');
                            this.flashOn = false;
                        }
                    },

                    flashBorder(ok) {
                        const el = document.getElementById('camera-container');
                        if (!el) return;
                        el.classList.remove('flash-success', 'flash-error');
                        void el.offsetWidth;
                        el.classList.add(ok ? 'flash-success' : 'flash-error');
                    },

                    showResult(result) {
                        clearTimeout(this.resultTimer);
                        const d = result.data || {};
                        const masuk = result.type === 'masuk';
                        this.resultData = {
                            type: result.type || 'masuk',
                            title: masuk ? '✅ Absen Masuk!' : '🔓 Absen Pulang!',
                            nama: d.nama || '-',
                            kelas: d.kelas || '-',
                            time: masuk ? 'Jam Masuk: ' + (d.time || '-') : 'Masuk: ' + (d.jam_masuk || '-') +
                                ' → Pulang: ' + (d.jam_pulang || '-')
                        };
                        this.showingResult = true;
                        this.resultTimer = setTimeout(() => {
                            this.showingResult = false;
                        }, 2500);
                    },

                    beep(ok) {
                        try {
                            const ctx = new(window.AudioContext || window.webkitAudioContext)();
                            const osc = ctx.createOscillator();
                            const g = ctx.createGain();
                            osc.connect(g);
                            g.connect(ctx.destination);
                            if (ok) {
                                osc.frequency.setValueAtTime(800, ctx.currentTime);
                                osc.frequency.setValueAtTime(1050, ctx.currentTime + 0.1);
                                g.gain.setValueAtTime(0.25, ctx.currentTime);
                                g.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
                                osc.start(ctx.currentTime);
                                osc.stop(ctx.currentTime + 0.2);
                            } else {
                                osc.frequency.setValueAtTime(300, ctx.currentTime);
                                g.gain.setValueAtTime(0.25, ctx.currentTime);
                                g.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                                osc.start(ctx.currentTime);
                                osc.stop(ctx.currentTime + 0.3);
                            }
                        } catch (e) {}
                    },

                    addToList(result) {
                        const list = document.getElementById('scan-list');
                        if (!list || !result.data) return;
                        const empty = list.querySelector('.flex-col');
                        if (empty) empty.remove();
                        const d = result.data;
                        const masuk = result.type === 'masuk';
                        const jam = masuk ? (d.time || '-') : (d.jam_masuk || '-') +
                            '<span class="text-[10px] text-gray-400 ml-1">↗ ' + (d.jam_pulang || '-') + '</span>';
                        const div = document.createElement('div');
                        div.className =
                            'flex items-center gap-3.5 px-5 py-3 hover:bg-gray-50/70 transition-colors anim-fade-up';
                        div.innerHTML = `
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xs font-bold text-indigo-600">${(d.nama || '?')[0].toUpperCase()}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">${d.nama || '-'}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">${d.nipd || d.nis || ''} · ${d.kelas || '-'}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs font-mono font-medium text-gray-600">${jam}</p>
                                <span class="inline-flex rounded-lg px-2 py-0.5 text-[10px] font-semibold ${masuk ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700'}">${masuk ? 'Masuk' : 'Pulang'}</span>
                            </div>`;
                        list.insertAdjacentElement('afterbegin', div);
                    },

                    notify(type, message, detail, icon) {
                        const id = Date.now() + Math.random();
                        this.notifications.push({
                            id,
                            type,
                            message,
                            detail: detail || '',
                            icon: icon || ICONS[type] || ICONS.info
                        });
                        setTimeout(() => {
                            this.notifications = this.notifications.filter(n => n.id !== id);
                        }, 4000);
                    },
                };
            }
        </script>
    @endpush
@endsection
