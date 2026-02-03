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
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="schedule-container">
            <div class="schedule-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3>Записи на сегодня ({{ now()->translatedFormat('j F') }})</h3>
                <div style="text-align: right;">
                    <span style="font-size: 14px; color: #888;">Мой доход за месяц:</span>
                    <strong style="color: var(--chocolate); font-size: 18px;">{{ number_format($earnings, 0, '.', ' ') }} ₽</strong>
                </div>
            </div>
            <div class="schedule-list">
                @forelse($bookings as $booking)
                <div class="schedule-item">
                    <div class="schedule-time">{{ $booking->start_time->format('H:i') }}</div>
                    <div class="client-info">
                        <h4>{{ $booking->user->name }}</h4>
                        <p>{{ $booking->service->name }}</p>
                    </div>
                    <div class="action-btns">
                        <button class="btn-action">Детали</button>
                        <button class="btn-action btn-complete">Завершить</button>
                    </div>
                </div>
                @empty
                <div class="schedule-item">
                    <p style="color: #888; text-align: center; width: 100%;">На сегодня записей нет</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
