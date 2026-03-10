<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление Базой Данных — Шоколад</title>
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

        .card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .table-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 18px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .table-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: var(--gold);
            transform: translateY(-3px);
        }

        .table-name {
            font-weight: 600;
            color: var(--chocolate);
            margin-bottom: 5px;
        }

        .table-desc {
            font-size: 12px;
            color: #888;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            background: #f5f5f5;
            color: #777;
            margin-top: 8px;
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
                <h1>База данных</h1>
                <p style="color:#777; margin-top:5px;">Просмотр и управление таблицами текущей базы проекта</p>
            </div>
        </div>

        <div class="card">
            @if(count($tableNames) > 0)
                <div class="tables-grid">
                    @foreach($tableNames as $tableName)
                        <a href="{{ route('director.db.table', $tableName) }}" class="table-card">
                            <div class="table-name">{{ $tableName }}</div>
                            <div class="table-desc">Открыть записи и выполнить операции CRUD</div>
                            <div class="badge">Таблица</div>
                        </a>
                    @endforeach
                </div>
            @else
                <p>Таблицы в базе данных не найдены.</p>
            @endif
        </div>
    </div>
</body>
</html>

