@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-wrap items-center justify-center gap-3">
        <div class="inline-flex flex-wrap items-center gap-2">
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $onEachSide = 1;

                $pages = collect([1]);
                for ($p = $current - $onEachSide; $p <= $current + $onEachSide; $p++) {
                    if ($p > 1 && $p < $last) {
                        $pages->push($p);
                    }
                }
                if ($last > 1) {
                    $pages->push($last);
                }
                $pages = $pages->unique()->sort()->values();
            @endphp

            @foreach ($pages as $i => $page)
                @if ($i > 0 && $page - $pages[$i - 1] > 1)
                    <span class="px-1 text-gray-400 select-none">…</span>
                @endif
                <a href="{{ $paginator->url($page) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-md bg-white border border-gray-200 text-sm transition-colors {{ $page == $current ? 'text-blue-600 font-bold' : 'text-gray-700 font-medium hover:text-blue-600' }}">
                    {{ $page }}
                </a>
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="inline-flex items-center gap-1 text-sm font-medium text-gray-700 hover:text-blue-600 whitespace-nowrap">
                次の{{ $paginator->perPage() }}件 <span aria-hidden="true">&raquo;</span>
            </a>
        @endif
    </nav>
@endif
