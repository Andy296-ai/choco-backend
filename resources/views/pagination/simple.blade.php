@if ($paginator->hasPages())
    <div class="pagination-simple">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-link disabled">← Назад</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link">← Назад</a>
        @endif

        {{-- Page Info --}}
        <span class="page-info">
            Страница {{ $paginator->currentPage() }} из {{ $paginator->lastPage() }}
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link">Вперёд →</a>
        @else
            <span class="page-link disabled">Вперёд →</span>
        @endif
    </div>
@endif
