<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель Администратора — Шоколад</title>
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

        .calendar-container {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .appointment-list {
            margin-top: 20px;
        }

        .appointment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #f5f5f5;
        }

        .appointment-time {
            font-weight: 600;
            color: var(--chocolate);
            width: 80px;
        }

        .appointment-info {
            flex: 1;
        }

        .appointment-info h4 {
            margin: 0;
            font-size: 14px;
        }

        .appointment-info p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #888;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .status-confirmed { background: #e8f5e9; color: #2e7d32; }
        .status-pending { background: #fff3e0; color: #ef6c00; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="active">Записи</a></li>
            <li class="nav-item"><a href="{{ route('admin.clients') }}">Клиенты</a></li>
            <li class="nav-item"><a href="{{ route('admin.masters') }}">Мастера</a></li>
            <li class="nav-item"><a href="{{ route('admin.warehouse') }}">Склад</a></li>
            <li class="nav-item"><a href="{{ route('admin.services') }}">Услуги</a></li>
        </ul>

    </div>

    <div class="main-content">
        <div class="header">
            <h1>Панель Администратора</h1>
            <div class="user-info">
                <span>{{ Session::get('user.name') }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h3>Записи на сегодня (26 января)</h3>
                <button class="logout-btn" style="background: var(--gold); border: none;">+ Новая запись</button>
            </div>
            <div class="appointment-list">
                <div class="appointment-item">
                    <div class="appointment-time">10:00</div>
                    <div class="appointment-info">
                        <h4>Анна Иванова</h4>
                        <p>Сложное окрашивание — Мастер: Елена К.</p>
                    </div>
                    <span class="status-badge status-confirmed">Подтверждено</span>
                </div>
                <div class="appointment-item">
                    <div class="appointment-time">11:30</div>
                    <div class="appointment-info">
                        <h4>Мария Петрова</h4>
                        <p>Маникюр + гель-лак — Мастер: Ольга С.</p>
                    </div>
                    <span class="status-badge status-confirmed">Подтверждено</span>
                </div>
                <div class="appointment-item">
                    <div class="appointment-time">13:00</div>
                    <div class="appointment-info">
                        <h4>Елена Сидорова</h4>
                        <p>Чистка лица — Мастер: Ирина М.</p>
                    </div>
                    <span class="status-badge status-pending">Ожидает</span>
                </div>
                <div class="appointment-item">
                    <div class="appointment-time">15:00</div>
                    <div class="appointment-info">
                        <h4>Светлана К.</h4>
                        <p>Стрижка женская — Мастер: Елена К.</p>
                    </div>
                    <span class="status-badge status-confirmed">Подтверждено</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
