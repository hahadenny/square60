@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><a class="pagination-link prev-btn" href="javascript:void(0);" style="cursor:auto;"></a></li>
        @else
            <li><a class="pagination-link prev-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev"></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class=" disabled"><span class="pagination-ellipsis">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span class="pagination-link current-page">{{ $page }}</span></li>
                    @else
                        <li><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li ><a class="pagination-link next-btn" href="{{ $paginator->nextPageUrl() }}" rel="next"></a></li>
        @else
            <li class="disabled"><a class="pagination-link next-btn" href="javascript:void(0);" style="cursor:auto;"></a></li>
        @endif
    </ul>
@endif
