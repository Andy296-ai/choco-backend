@if ($paginator->hasPages())
<nav class="choco-pagination choco-pagination--simple" role="navigation" aria-label="Навигация">

    @if ($paginator->onFirstPage())
        <span class="choco-page choco-page--disabled">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="margin-right:6px"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Назад
        </span>
    @else
        <a class="choco-page choco-page--nav" href="{{ $paginator->previousPageUrl() }}" rel="prev">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="margin-right:6px"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Назад
        </a>
    @endif

    <span class="choco-page-info">Стр. {{ $paginator->currentPage() }}</span>

    @if ($paginator->hasMorePages())
        <a class="choco-page choco-page--nav" href="{{ $paginator->nextPageUrl() }}" rel="next">
            Вперёд
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="margin-left:6px"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    @else
        <span class="choco-page choco-page--disabled">
            Вперёд
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="margin-left:6px"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
    @endif

</nav>
@endif
