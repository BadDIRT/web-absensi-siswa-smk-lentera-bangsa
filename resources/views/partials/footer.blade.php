{{-- ══════════════════════════════════════════════
     Footer
     ══════════════════════════════════════════════ --}}
<footer class="shrink-0 border-t border-gray-100 bg-white px-4 py-3 md:px-6">
    <div class="flex flex-col items-center justify-between gap-1 text-xs text-gray-400 sm:flex-row">
        <span>&copy; {{ date('Y') }} SMK Lentera Bangsa. Sistem Absensi Digital.</span>
        <span>Dibuat dengan Laravel {{ app()->version() }}</span>
    </div>
</footer>
