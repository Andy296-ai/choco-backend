@extends('layouts.director')

@section('title', 'Таблица ' . $table . ' — Шоколад')

@section('styles')
<style>
    .db-table-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .db-table-header h1 {
        margin: 0;
        font-family: 'Raleway', sans-serif;
        color: var(--chocolate);
        font-size: 24px;
    }

    .db-table-header p { color: #888; margin: 4px 0 0; font-size: 13px; }

    .btn {
        display: inline-block;
        padding: 9px 18px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .btn:hover { opacity: 0.85; }
    .btn-gold     { background: var(--gold); color: var(--chocolate); }
    .btn-grey     { background: #eee; color: #555; }
    .btn-danger   { background: #f44336; color: #fff; }
    .btn-choco    { background: var(--chocolate); color: #fff; }
    .btn-sm       { padding: 5px 12px; font-size: 12px; }

    .filter-bar {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .filter-bar input {
        padding: 9px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        outline: none;
        flex: 1;
        min-width: 180px;
    }

    .filter-bar input:focus { border-color: var(--gold); }

    .db-table-wrap { overflow-x: auto; }

    .db-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .db-table th {
        text-align: left;
        padding: 10px 12px;
        border-bottom: 2px solid #f0f0f0;
        color: #888;
        font-weight: 600;
        white-space: nowrap;
    }

    .db-table th a {
        color: inherit;
        text-decoration: none;
    }

    .db-table th a:hover { color: var(--chocolate); }

    .db-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f5f5f5;
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .db-table tr:hover td { background: #fafafa; }

    .badge-null { color: #bbb; font-style: italic; font-size: 12px; }

    .actions-cell { text-align: right; white-space: nowrap; }

    @media (max-width: 768px) {
        .db-table-header { flex-direction: column; }
        .db-table-header .btn-row { display: flex; gap: 8px; flex-wrap: wrap; }
        .db-table td { max-width: 120px; }
    }
</style>
@endsection

@section('content')
    <div class="db-table-header">
        <div>
            <h1>Таблица: <span style="color:var(--gold);">{{ $table }}</span></h1>
            <p>Всего записей: {{ $rows->total() }}</p>
        </div>
        <div class="btn-row" style="display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('director.db.index') }}" class="btn btn-grey">← Список таблиц</a>
            <a href="{{ route('director.db.create', $table) }}" class="btn btn-gold">+ Добавить запись</a>
        </div>
    </div>

    <div class="content-card">
        <form method="GET" action="{{ route('director.db.table', $table) }}" class="filter-bar">
            <input type="text" name="search" value="{{ $search }}" placeholder="Поиск по таблице...">
            <input type="hidden" name="sort" value="{{ $sortColumn }}">
            <input type="hidden" name="direction" value="{{ $sortDirection }}">
            <button type="submit" class="btn btn-grey">Поиск</button>
            @if($search)
                <a href="{{ route('director.db.table', $table) }}" class="btn btn-grey">Сбросить</a>
            @endif
        </form>

        @if(session('success'))
            <div style="background:#e8f5e9; color:#2e7d32; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#ffebee; color:#c62828; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="db-table-wrap">
            <table class="db-table">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th>
                                <a href="{{ route('director.db.table', [
                                    'table'     => $table,
                                    'sort'      => $column['name'],
                                    'direction' => $sortColumn === $column['name'] && $sortDirection === 'asc' ? 'desc' : 'asc',
                                    'search'    => $search,
                                ]) }}">
                                    {{ $column['name'] }}
                                    @if($sortColumn === $column['name'])
                                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th style="text-align:right;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        @php
                            $pkCol = 'id';
                            foreach ($columns as $col) {
                                if ($col['primary']) { $pkCol = $col['name']; break; }
                            }
                            $pkVal = $row->{$pkCol};
                        @endphp
                        <tr>
                            @foreach($columns as $column)
                                @php $val = $row->{$column['name']}; @endphp
                                <td title="{{ $val }}">
                                    @if($val === null)
                                        <span class="badge-null">NULL</span>
                                    @elseif(is_string($val) && mb_strlen($val) > 60)
                                        {{ mb_substr($val, 0, 60) }}…
                                    @else
                                        {{ $val }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="actions-cell">
                                <a href="{{ route('director.db.edit', [$table, $pkVal]) }}"
                                   class="btn btn-grey btn-sm">Редактировать</a>
                                <form method="POST"
                                      action="{{ route('director.db.destroy', [$table, $pkVal]) }}"
                                      style="display:inline-block;"
                                      onsubmit="return confirm('Удалить эту запись?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}"
                                style="text-align:center; padding:40px; color:#888;">
                                Записей не найдено
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="choco-pagination-wrap">{{ $rows->links() }}</div>
    </div>
@endsection
