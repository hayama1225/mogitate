@if ($paginator->hasPages())
<nav class="paginator" aria-label="Pagination">
    <ul>
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
        <li class="page disabled">&lt;</li>
        @else
        <li class="page"><a href="{{ $paginator->previousPageUrl() }}">&lt;</a></li>
        @endif

        {{-- Numbers --}}
        @foreach ($elements as $element)
        @if (is_string($element))
        <li class="page dots">…</li>
        @endif
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="page is-current"><span>{{ $page }}</span></li>
        @else
        <li class="page"><a href="{{ $url }}">{{ $page }}</a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <li class="page"><a href="{{ $paginator->nextPageUrl() }}">&gt;</a></li>
        @else
        <li class="page disabled">&gt;</li>
        @endif
    </ul>
</nav>
@endif