@props(['title', 'back' => null, 'action' => null])

<div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-3">
        @if ($back)
            <a href="{{ $back }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
        @endif
        <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
    </div>

    @if ($action)
        <div>
            {{ $action }}
        </div>
    @endif
</div>
