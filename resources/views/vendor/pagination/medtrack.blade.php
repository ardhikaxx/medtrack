<div class="medtrack-pagination-wrapper">
    <div class="pagination-info-custom">
        <span class="info-text">Menampilkan</span>
        <span class="info-number">{{ $paginator->firstItem() ?? 0 }}</span>
        <span class="info-text">-</span>
        <span class="info-number">{{ $paginator->lastItem() ?? 0 }}</span>
        <span class="info-text">dari</span>
        <span class="info-number total">{{ $paginator->total() }}</span>
        <span class="info-text">data</span>
    </div>
    
    <nav class="pagination-nav-custom">
        <ul class="pagination-custom">
            @if ($paginator->onFirstPage())
                <li class="page-item-custom disabled">
                    <span class="page-link-custom"><i class="fas fa-angle-left"></i></span>
                </li>
            @else
                <li class="page-item-custom">
                    <a class="page-link-custom" href="{{ $paginator->previousPageUrl() }}"><i class="fas fa-angle-left"></i></a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item-custom disabled"><span class="page-link-custom">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item-custom active"><span class="page-link-custom">{{ $page }}</span></li>
                        @else
                            <li class="page-item-custom"><a class="page-link-custom" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item-custom">
                    <a class="page-link-custom" href="{{ $paginator->nextPageUrl() }}"><i class="fas fa-angle-right"></i></a>
                </li>
            @else
                <li class="page-item-custom disabled">
                    <span class="page-link-custom"><i class="fas fa-angle-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
</div>
