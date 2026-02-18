<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мое расписание — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
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

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--chocolate);
            color: var(--white);
            position: fixed;
            padding: 30px 20px;
            overflow-y: auto;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            margin-bottom: 40px;
            text-align: center;
            font-size: 20px;
        }

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

        .schedule-header-grid {
            display: grid;
            grid-template-columns: 150px 100px 150px 150px;
            gap: 20px;
            font-weight: 600;
            color: #888;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: 150px 100px 150px 150px;
            gap: 20px;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .day-name {
            font-weight: 600;
            color: var(--chocolate);
            text-transform: capitalize;
        }

        .btn-save {
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            width: 100%;
            max-width: 300px;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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

            .schedule-header-grid,
            .schedule-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .schedule-header-grid > div {
                display: none;
            }

            .schedule-header-grid > div:first-child {
                display: block;
                font-weight: 600;
                margin-bottom: 10px;
            }

            .schedule-grid {
                padding: 15px;
                border: 1px solid #eee;
                border-radius: 5px;
                margin-bottom: 10px;
            }

            .day-name {
                grid-column: 1 / -1;
                font-size: 16px;
                margin-bottom: 10px;
            }

            .schedule-grid > div:not(.day-name) {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .schedule-grid > div:not(.day-name)::before {
                content: attr(data-label);
                font-weight: 600;
                color: #888;
                min-width: 80px;
            }

            .schedule-grid > div:nth-child(2)::before {
                content: "Работаю:";
            }

            .schedule-grid > div:nth-child(3)::before {
                content: "Начало:";
            }

            .schedule-grid > div:nth-child(4)::before {
                content: "Конец:";
            }

            .btn-save {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 18px;
            }

            .content-card {
                padding: 10px;
            }

            .schedule-grid {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>
    
    <div class="sidebar" id="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.schedule') }}" class="active">Расписание</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}">Портфолио</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Управление расписанием</h1>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">Выйти</button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="content-card">
            <form action="{{ route('specialist.schedule.update') }}" method="POST">
                @csrf
                <div class="schedule-header-grid">
                    <div>День недели</div>
                    <div>Работаю</div>
                    <div>Начало</div>
                    <div>Конец</div>
                </div>

                @php
                    $daysRu = [
                        'monday' => 'Понедельник',
                        'tuesday' => 'Вторник',
                        'wednesday' => 'Среда',
                        'thursday' => 'Четверг',
                        'friday' => 'Пятница',
                        'saturday' => 'Суббота',
                        'sunday' => 'Воскресенье'
                    ];
                @endphp

                @foreach($schedules as $schedule)
                <div class="schedule-grid">
                    <div class="day-name">{{ $daysRu[$schedule->day_of_week] ?? $schedule->day_of_week }}</div>
                    <div>
                        <input type="hidden" name="schedules[{{ $schedule->id }}][is_working]" value="0">
                        <input type="checkbox" name="schedules[{{ $schedule->id }}][is_working]" value="1" {{ $schedule->is_working ? 'checked' : '' }} style="width: 20px; height: 20px;">
                    </div>
                    <div>
                        <input type="time" name="schedules[{{ $schedule->id }}][start_time]" value="{{ $schedule->start_time }}" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd; width: 100%;">
                    </div>
                    <div>
                        <input type="time" name="schedules[{{ $schedule->id }}][end_time]" value="{{ $schedule->end_time }}" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd; width: 100%;">
                    </div>
                </div>
                @endforeach

                <button type="submit" class="btn-save">Сохранить изменения</button>
            </form>
        </div>
    </div>

    <script>
        // Мобильное меню
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
            
            // Закрытие меню при клике вне его
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(event.target) && 
                    !mobileMenuToggle.contains(event.target) &&
                    sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
            });
        }
    </script>
</body>
</html>
