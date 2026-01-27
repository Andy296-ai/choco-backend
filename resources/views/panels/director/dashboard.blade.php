<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель Директора — Шоколад</title>
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

        /* Sidebar */
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

        /* Main Content */
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: none;
            border: 1px solid var(--chocolate);
            color: var(--chocolate);
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .card h3 {
            font-size: 14px;
            color: #888;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .card .value {
            font-size: 32px;
            font-weight: 600;
            color: var(--chocolate);
        }

        .table-container {
            margin-top: 40px;
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #f5f5f5;
            color: #888;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}" class="active">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.reports') }}">Отчеты</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}">Настройки</a></li>
        </ul>

    </div>

    <div class="main-content">
        <div class="header">
            <h1>Панель Директора</h1>
            <div class="user-info">
                <span>{{ Session::get('user.name') }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <h3>Выручка за месяц</h3>
                <div class="value">1 250 000 ₽</div>
            </div>
            <div class="card">
                <h3>Новые клиенты</h3>
                <div class="value">142</div>
            </div>
            <div class="card">
                <h3>Средний чек</h3>
                <div class="value">3 200 ₽</div>
            </div>
            <div class="card">
                <h3>Загрузка салона</h3>
                <div class="value">85%</div>
            </div>
        </div>

        <div class="table-container">
            <h3>Последние транзакции</h3>
            <table>
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Клиент</th>
                        <th>Услуга</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>26.01.2026</td>
                        <td>Анна Иванова</td>
                        <td>Сложное окрашивание</td>
                        <td>8 500 ₽</td>
                        <td><span style="color: green;">Оплачено</span></td>
                    </tr>
                    <tr>
                        <td>26.01.2026</td>
                        <td>Мария Петрова</td>
                        <td>Маникюр + гель-лак</td>
                        <td>2 200 ₽</td>
                        <td><span style="color: green;">Оплачено</span></td>
                    </tr>
                    <tr>
                        <td>25.01.2026</td>
                        <td>Елена Сидорова</td>
                        <td>Чистка лица</td>
                        <td>3 500 ₽</td>
                        <td><span style="color: green;">Оплачено</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
