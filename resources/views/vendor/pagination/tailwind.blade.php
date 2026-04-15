@if ($paginator->hasPages())
    <nav class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>
        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="rounded-lg px-3 py-1.5 text-sm text-gray-300">←</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="rounded-lg px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 transition-colors">←</a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-sm text-gray-400">{{ $element }}</span>
                @elseif (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="rounded-lg bg-brand-600 px-3 py-1.5 text-sm font-medium text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="rounded-lg px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="rounded-lg px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 transition-colors">→</a>
            @else
                <span class="rounded-lg px-3 py-1.5 text-sm text-gray-300">→</span>
            @endif
        </div>
    </nav>
@endif
