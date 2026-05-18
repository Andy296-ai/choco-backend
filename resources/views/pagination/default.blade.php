@if ($paginator->hasPages())
<nav class="choco-pagination" role="navigation" aria-label="Навигация по страницам">

    {{-- Кнопка «Назад» --}}
    @if ($paginator->onFirstPage())
        <span class="choco-page choco-page--disabled" aria-disabled="true" aria-label="Назад">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
    @else
        <a class="choco-page choco-page--arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Назад">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    @endif

    {{-- Номера страниц --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="choco-page choco-page--dots" aria-hidden="true">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="choco-page choco-page--active" aria-current="page">{{ $page }}</span>
                @else
                    <a class="choco-page" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Кнопка «Вперёд» --}}
    @if ($paginator->hasMorePages())
        <a class="choco-page choco-page--arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Вперёд">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    @else
        <span class="choco-page choco-page--disabled" aria-disabled="true" aria-label="Вперёд">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
    @endif

</nav>
@endif
