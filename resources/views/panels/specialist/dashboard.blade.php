<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель Специалиста — Шоколад</title>
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

        .schedule-container {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .schedule-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #f5f5f5;
            padding-bottom: 10px;
        }

        .schedule-item {
            display: grid;
            grid-template-columns: 100px 1fr 150px;
            padding: 20px;
            border-bottom: 1px solid #f5f5f5;
            align-items: center;
        }

        .schedule-time {
            font-weight: 600;
            color: var(--chocolate);
            font-size: 18px;
        }

        .client-info h4 {
            margin: 0;
            font-size: 16px;
            color: var(--chocolate);
        }

        .client-info p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #888;
        }

        .action-btns {
            text-align: right;
        }

        .btn-action {
            padding: 5px 12px;
            border-radius: 3px;
            font-size: 12px;
            cursor: pointer;
            border: 1px solid #ddd;
            background: #fff;
            margin-left: 5px;
        }

        .btn-complete {
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}" class="active">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}">Портфолио</a></li>
            <li class="nav-item"><a href="{{ route('specialist.materials') }}">Материалы</a></li>
        </ul>

    </div>

    <div class="main-content">
        <div class="header">
            <h1>Панель Специалиста</h1>
            <div class="user-info">
                <span>{{ Session::get('user.name') }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="schedule-container">
            <div class="schedule-header">
                <h3>Записи на сегодня (26 января)</h3>
            </div>
            <div class="schedule-list">
                <div class="schedule-item">
                    <div class="schedule-time">10:00</div>
                    <div class="client-info">
                        <h4>Анна Иванова</h4>
                        <p>Сложное окрашивание (Airtouch)</p>
                    </div>
                    <div class="action-btns">
                        <button class="btn-action">Детали</button>
                        <button class="btn-action btn-complete">Завершить</button>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-time">15:00</div>
                    <div class="client-info">
                        <h4>Светлана К.</h4>
                        <p>Стрижка женская + укладка</p>
                    </div>
                    <div class="action-btns">
                        <button class="btn-action">Детали</button>
                        <button class="btn-action btn-complete">Завершить</button>
                    </div>
                </div>
                <div class="schedule-item" style="opacity: 0.5;">
                    <div class="schedule-time">17:30</div>
                    <div class="client-info">
                        <h4>Екатерина Д.</h4>
                        <p>Тонирование волос</p>
                    </div>
                    <div class="action-btns">
                        <button class="btn-action">Детали</button>
                        <button class="btn-action btn-complete">Завершить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
