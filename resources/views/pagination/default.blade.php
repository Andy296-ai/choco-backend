@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();

    /*
     * Строим список видимых страниц по алгоритму:
     *   Всегда: 1, 2, 3
     *   "..."   если current > 5
     *   current-1, current, current+1  (если не пересекаются с другими группами)
     *   "..."   если current < last - 4
     *   Всегда: last-2, last-1, last
     *
     * Для маленьких списков (≤ 9 страниц) выводим все без многоточий.
     */

    $pages = [];

    if ($last <= 9) {
        // Маленький список — все страницы
        for ($i = 1; $i <= $last; $i++) {
            $pages[] = $i;
        }
    } else {
        // Начальная группа: 1 2 3
        $head = [1, 2, 3];
        // Хвостовая группа: last-2, last-1, last
        $tail = [$last - 2, $last - 1, $last];
        // Окно вокруг текущей: current-1, current, current+1
        $window = array_filter(
            [$current - 1, $current, $current + 1],
            fn($p) => $p >= 1 && $p <= $last
        );

        // Объединяем, сортируем, убираем дубли
        $all = array_unique(array_merge($head, array_values($window), $tail));
        sort($all);

        // Строим итоговый список с "..." там, где есть пропуск > 1
        $prev = null;
        foreach ($all as $p) {
            if ($prev !== null && $p - $prev > 1) {
                $pages[] = '...';
            }
            $pages[] = $p;
            $prev = $p;
        }
    }
@endphp
<nav class="choco-pagination" role="navigation" aria-label="Навигация по страницам">

    {{-- Кнопка «Назад» --}}
    @if ($paginator->onFirstPage())
        <span class="choco-page choco-page--disabled" aria-disabled="true">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
    @else
        <a class="choco-page choco-page--arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    @endif

    {{-- Номера страниц --}}
    @foreach ($pages as $page)
        @if ($page === '...')
            <span class="choco-page choco-page--dots">…</span>
        @elseif ($page == $current)
            <span class="choco-page choco-page--active" aria-current="page">{{ $page }}</span>
        @else
            <a class="choco-page" href="{{ $paginator->url($page) }}">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Кнопка «Вперёд» --}}
    @if ($paginator->hasMorePages())
        <a class="choco-page choco-page--arrow" href="{{ $paginator->nextPageUrl() }}" rel="next">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    @else
        <span class="choco-page choco-page--disabled" aria-disabled="true">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
    @endif

</nav>
@endif
