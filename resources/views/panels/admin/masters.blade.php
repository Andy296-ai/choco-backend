<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мастера — Шоколад</title>
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
        }

        .master-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .master-card {
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .master-card h4 {
            margin: 10px 0 5px;
            color: var(--chocolate);
        }

        .master-card p {
            font-size: 12px;
            color: #888;
            margin-bottom: 15px;
        }

        .btn-schedule {
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}">Записи</a></li>
            <li class="nav-item"><a href="{{ route('admin.clients') }}">Клиенты</a></li>
            <li class="nav-item"><a href="{{ route('admin.masters') }}" class="active">Мастера</a></li>
            <li class="nav-item"><a href="{{ route('admin.warehouse') }}">Склад</a></li>
            <li class="nav-item"><a href="{{ route('admin.services') }}">Услуги</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Мастера</h1>
        </div>

        <div class="content-card">
            <div class="master-grid">
                <div class="master-card">
                    <h4>Елена К.</h4>
                    <p>Топ-стилист</p>
                    <button class="btn-schedule">График работы</button>
                </div>
                <div class="master-card">
                    <h4>Ольга С.</h4>
                    <p>Мастер маникюра</p>
                    <button class="btn-schedule">График работы</button>
                </div>
                <div class="master-card">
                    <h4>Ирина М.</h4>
                    <p>Косметолог</p>
                    <button class="btn-schedule">График работы</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
