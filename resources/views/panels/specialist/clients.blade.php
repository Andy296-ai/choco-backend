<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мои клиенты — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Raleway:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --chocolate: #3E2723;
            --gold: #D4AF37;
            --cream: #FFF8E1;
            --white: #FFFFFF;
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
        }

        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            background: var(--chocolate);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px;
        }

        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:900; }
        .sidebar-overlay.active { display:block; }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--chocolate);
            color: var(--white);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .sidebar-top { padding: 30px 20px 20px; flex-shrink: 0; }

        .sidebar h2 {
            font-family: 'Raleway', sans-serif;
            color: var(--gold);
            margin: 0 0 30px;
            text-align: center;
            font-size: 20px;
        }

        .sidebar-bottom {
            margin-top: auto; padding: 16px 20px 24px;
            border-top: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;
        }
        .sidebar-user { display:flex; align-items:center; gap:10px; margin-bottom:14px; }
        .sidebar-avatar {
            width:36px; height:36px; border-radius:50%;
            background:rgba(212,175,55,0.25);
            display:flex; align-items:center; justify-content:center;
            font-size:14px; font-weight:700; color:var(--gold); flex-shrink:0;
        }
        .sidebar-user-info { flex:1; min-width:0; }
        .sidebar-user-name { font-size:13px; font-weight:600; color:var(--white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
        .sidebar-user-role { font-size:11px; color:rgba(255,255,255,0.5); display:block; margin-top:2px; }
        .sidebar-logout-btn {
            width:100%; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.18);
            color:rgba(255,255,255,0.75); padding:8px 12px; border-radius:6px; cursor:pointer;
            font-size:12px; font-family:'Montserrat',sans-serif; font-weight:600; text-transform:uppercase;
            letter-spacing:0.5px; transition:background 0.2s,color 0.2s; text-align:center;
        }
        .sidebar-logout-btn:hover { background:rgba(244,67,54,0.25); border-color:rgba(244,67,54,0.4); color:#ff8a80; }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
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
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: var(--chocolate);
        }

        .content-card {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 600px;
        }

        th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #f5f5f5;
            color: #888;
            font-size: 14px;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
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

        /* Мобильная версия */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 60px 15px 15px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header h1 {
                font-size: 20px;
            }

            .content-card {
                padding: 15px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px 8px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 18px;
            }

            table {
                font-size: 11px;
            }

            th, td {
                padding: 8px 5px;
            }
        }
    </style>
    <style>{!! file_get_contents(resource_path('css/pagination.css')) !!}</style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}" class="active">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.schedule') }}">Расписание</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}">Портфолио</a></li>
        </ul>
    </div>
    <div class="sidebar-bottom">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'М', 0, 1)) }}</div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ auth()->user()->name ?? '—' }}</span>
                <span class="sidebar-user-role">Мастер</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout-btn">Выйти</button>
        </form>
    </div>
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
                    @forelse($clients as $data)
                        <tr>
                            <td>{{ $data['client']->name }}</td>
                            <td>{{ $data['client']->phone ?? 'Не указан' }}</td>
                            <td>{{ $data['last_booking']->service->name ?? 'Неизвестно' }}</td>
                            <td>{{ $data['last_booking']->start_time->format('d.m.Y H:i') }}</td>
                            <td><span style="color: #888;">{{ $data['client']->telegram_username ? '@'.$data['client']->telegram_username : '-' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div style="text-align:center; padding:50px 20px; color:#888;">
                                    <div style="font-size:44px; margin-bottom:14px; opacity:0.3;">👥</div>
                                    <strong style="font-family:'Raleway',serif; color:var(--chocolate); font-size:16px; display:block; margin-bottom:8px;">Клиентов пока нет</strong>
                                    <span style="font-size:13px;">Клиенты появятся здесь после первых записей</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                {{ $clients->links() }}
            </div>
        </div>
    </div>

    <script>
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => { sidebar.classList.toggle('open'); sidebarOverlay.classList.toggle('active'); });
            sidebarOverlay.addEventListener('click', () => { sidebar.classList.remove('open'); sidebarOverlay.classList.remove('active'); });
        }
    </script>
</body>
</html>
