<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мои клиенты — Шоколад</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}" class="active">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}">Портфолио</a></li>
            <li class="nav-item"><a href="{{ route('specialist.materials') }}">Материалы</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Мои клиенты</h1>
        </div>

        <div class="content-card">
            <table>
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Последняя услуга</th>
                        <th>Дата</th>
                        <th>Заметки</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Анна Иванова</td>
                        <td>+7 (900) 111-22-33</td>
                        <td>Сложное окрашивание</td>
                        <td>26.01.2026</td>
                        <td><a href="#" style="color: var(--gold);">Посмотреть</a></td>
                    </tr>
                    <tr>
                        <td>Светлана К.</td>
                        <td>+7 (900) 555-66-77</td>
                        <td>Стрижка женская</td>
                        <td>26.01.2026</td>
                        <td><a href="#" style="color: var(--gold);">Посмотреть</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
