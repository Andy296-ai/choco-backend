<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Материалы — Шоколад</title>
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

        .material-list {
            list-style: none;
            padding: 0;
        }

        .material-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .btn-request {
            background: var(--chocolate);
            color: var(--white);
            border: none;
            padding: 5px 15px;
            border-radius: 3px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}">Портфолио</a></li>
            <li class="nav-item"><a href="{{ route('specialist.materials') }}" class="active">Материалы</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Материалы</h1>
            <button class="btn-add" style="background: var(--gold); border: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; cursor: pointer;">Запросить материалы</button>
        </div>

        <div class="content-card">
            <h3>Мои остатки</h3>
            <ul class="material-list">
                <li class="material-item">
                    <span>Краска L'Oreal Majirel 7.1</span>
                    <span>3 шт.</span>
                </li>
                <li class="material-item">
                    <span>Оксидант 6%</span>
                    <span>1.5 л.</span>
                </li>
                <li class="material-item">
                    <span>Перчатки нитриловые (M)</span>
                    <span>20 пар</span>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
