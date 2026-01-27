<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Настройки — Шоколад</title>
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-save {
            background: var(--chocolate);
            color: var(--white);
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
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
            <li class="nav-item"><a href="{{ route('director.finance') }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}" class="active">Настройки</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Настройки</h1>
        </div>

        <div class="content-card">
            <form>
                <div class="form-group">
                    <label>Название салона</label>
                    <input type="text" value="Шоколад">
                </div>
                <div class="form-group">
                    <label>Контактный телефон</label>
                    <input type="text" value="+7 (926) 607-07-07">
                </div>
                <div class="form-group">
                    <label>Email для уведомлений</label>
                    <input type="email" value="info@chocolate-sp.ru">
                </div>
                <div class="form-group">
                    <label>Валюта</label>
                    <input type="text" value="RUB (₽)">
                </div>
                <button type="button" class="btn-save">Сохранить изменения</button>
            </form>
        </div>
    </div>
</body>
</html>
