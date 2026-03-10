<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Таблица {{ $table }} — Управление БД</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --chocolate: #3E2723;
            --gold: #D4AF37;
            --cream: #FFF8E1;
            --white: #FFFFFF;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--chocolate);
            color: var(--white);
            position: fixed;
            padding: 30px 20px;
        }

        .sidebar h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            margin-bottom: 40px;
            text-align: center;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 15px;
        }

        .nav-item a {
            color: #ccc;
            text-decoration: none;
            font-size: 14px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-item a:hover,
        .nav-item a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--gold);
        }

        .main-content {
            margin-left: 298px;
            flex: 1;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: var(--chocolate);
            font-family: 'Playfair Display', serif;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gold);
            color: var(--chocolate);
        }

        .btn-secondary {
            background: #eee;
            color: #555;
        }

        .btn-danger {
            background: #f44336;
            color: #fff;
        }

        .card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .filters {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 15px;
        }

        .filters input {
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #f0f0f0;
            font-size: 12px;
            color: #777;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 13px;
        }

        th a {
            color: inherit;
            text-decoration: none;
        }

        .pagination {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 13px;
        }

        .badge-null {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}">Настройки</a></li>
            <li class="nav-item"><a href="{{ route('director.db.index') }}" class="active">База данных</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Таблица: {{ $table }}</h1>
                <p style="color:#777; margin-top:5px;">Всего записей: {{ $rows->total() }}</p>
            </div>
            <div style="display:flex; gap:10px;">
                <a href="{{ route('director.db.index') }}" class="btn btn-secondary">← К списку таблиц</a>
                <a href="{{ route('director.db.create', $table) }}" class="btn btn-primary">+ Добавить запись</a>
            </div>
        </div>

        <div class="card">
            <form method="GET" action="{{ route('director.db.table', $table) }}" class="filters">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Поиск по таблице..."
                >
                <button type="submit" class="btn btn-secondary">Поиск</button>
                @if($search)
                    <a href="{{ route('director.db.table', $table) }}" class="btn btn-secondary">Сбросить</a>
                @endif
            </form>

            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            @foreach($columns as $column)
                                <th>
                                    <a href="{{ route('director.db.table', [
                                        'table' => $table,
                                        'sort' => $column['name'],
                                        'direction' => $sortColumn === $column['name'] && $sortDirection === 'asc' ? 'desc' : 'asc',
                                        'search' => $search,
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
                            <tr>
                                @foreach($columns as $column)
                                    @php
                                        $value = $row->{$column['name']};
                                    @endphp
                                    <td>
                                        @if($value === null)
                                            <span class="badge-null">NULL</span>
                                        @elseif(is_string($value) && mb_strlen($value) > 80)
                                            {{ mb_substr($value, 0, 80) . '…' }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                @endforeach
                                @php
                                    $primaryKeyColumn = 'id';
                                    foreach ($columns as $col) {
                                        if ($col['primary']) {
                                            $primaryKeyColumn = $col['name'];
                                            break;
                                        }
                                    }
                                    $primaryKeyValue = $row->{$primaryKeyColumn};
                                @endphp
                                <td style="text-align:right; white-space:nowrap;">
                                    <a href="{{ route('director.db.edit', [$table, $primaryKeyValue]) }}" class="btn btn-secondary">Редактировать</a>
                                    <form method="POST" action="{{ route('director.db.destroy', [$table, $primaryKeyValue]) }}" style="display:inline-block;" onsubmit="return confirm('Удалить эту запись?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" style="text-align:center; padding:20px; color:#777;">
                                    Записей не найдено.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rows->hasPages())
                <div class="pagination">
                    @if($rows->onFirstPage())
                        <span>← Назад</span>
                    @else
                        <a href="{{ $rows->previousPageUrl() }}">← Назад</a>
                    @endif

                    <span>Страница {{ $rows->currentPage() }} из {{ $rows->lastPage() }}</span>

                    @if($rows->hasMorePages())
                        <a href="{{ $rows->nextPageUrl() }}">Вперёд →</a>
                    @else
                        <span>Вперёд →</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>

