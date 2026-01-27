<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Финансы — Шоколад</title>
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

        .nav-item a:hover, .nav-item a.active {
            background-color: rgba(255,255,255,0.1);
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
            margin-bottom: 40px;
        }

        .content-card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .finance-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .summary-item h4 {
            font-size: 14px;
            color: #888;
            margin-bottom: 10px;
        }

        .summary-item .amount {
            font-size: 24px;
            font-weight: 600;
            color: var(--chocolate);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.reports') }}">Отчеты</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}" class="active">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}">Настройки</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Финансы</h1>
        </div>

        <div class="content-card">
            <h3>Итоги месяца</h3>
            <div class="finance-summary">
                <div class="summary-item">
                    <h4>Общая выручка</h4>
                    <div class="amount">1 250 000 ₽</div>
                </div>
                <div class="summary-item">
                    <h4>Расходы</h4>
                    <div class="amount" style="color: #d32f2f;">450 000 ₽</div>
                </div>
                <div class="summary-item">
                    <h4>Чистая прибыль</h4>
                    <div class="amount" style="color: #2e7d32;">800 000 ₽</div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h3>Статьи расходов</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <span>Аренда помещения</span>
                    <span style="font-weight: 600;">150 000 ₽</span>
                </li>
                <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <span>Закупка материалов</span>
                    <span style="font-weight: 600;">120 000 ₽</span>
                </li>
                <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <span>Зарплатный фонд</span>
                    <span style="font-weight: 600;">180 000 ₽</span>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
