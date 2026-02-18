<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель Специалиста — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />
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

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--chocolate);
            color: var(--white);
            position: fixed;
            padding: 30px 20px;
            overflow-y: auto;
            z-index: 100;
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
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .schedule-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #f5f5f5;
            padding-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .schedule-header h3 {
            margin: 0;
            font-size: 18px;
            color: var(--chocolate);
        }

        .filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            display: block;
            font-size: 12px;
            margin-bottom: 5px;
            color: #666;
            font-weight: 600;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .view-btn {
            padding: 10px 20px;
            border: 1px solid var(--chocolate);
            background: white;
            color: var(--chocolate);
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .view-btn.active {
            background: var(--chocolate);
            color: white;
        }

        .schedule-item {
            display: grid;
            grid-template-columns: 80px 1fr 120px;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #f5f5f5;
            align-items: center;
        }

        .schedule-time {
            font-weight: 600;
            color: var(--chocolate);
            font-size: 16px;
        }

        .client-info h4 {
            margin: 0;
            font-size: 15px;
            color: var(--chocolate);
        }

        .client-info p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #888;
        }

        .action-btns {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            border: 1px solid #ddd;
            background: #fff;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background: #f5f5f5;
        }

        .btn-complete {
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            font-weight: 600;
        }

        .btn-complete:hover {
            background: #c9a02e;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending { background: #fff3e0; color: #ef6c00; }
        .status-confirmed { background: #e8f5e9; color: #2e7d32; }
        .status-completed { background: #e3f2fd; color: #1565c0; }
        .status-cancelled { background: #ffebee; color: #c62828; }

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

        .earnings-info {
            text-align: right;
        }

        .earnings-info span {
            font-size: 14px;
            color: #888;
            display: block;
        }

        .earnings-info strong {
            color: var(--chocolate);
            font-size: 18px;
        }

        #calendar-view {
            display: none;
        }

        #list-view {
            display: block;
        }

        /* Мобильная версия */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
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

            .schedule-item {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .schedule-time {
                font-size: 18px;
                font-weight: 700;
            }

            .action-btns {
                width: 100%;
            }

            .btn-action {
                flex: 1;
            }

            .filters {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }

            .view-toggle {
                width: 100%;
            }

            .view-btn {
                flex: 1;
            }

            .earnings-info {
                text-align: left;
                width: 100%;
            }

            .schedule-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 20px;
            }

            .schedule-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>
    
    <div class="sidebar" id="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}" class="active">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.schedule') }}">Расписание</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}">Портфолио</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Панель Специалиста</h1>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div class="earnings-info">
                    <span>Доход за месяц:</span>
                    <strong>{{ number_format($earnings, 0, '.', ' ') }} ₽</strong>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="schedule-container">
            <div class="schedule-header">
                <h3>Мои записи</h3>
            </div>
            
            <!-- Фильтры и поиск -->
            <form method="GET" action="{{ route('specialist.dashboard') }}" class="filters">
                <div class="filter-group">
                    <label>Поиск</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Клиент, услуга...">
                </div>
                <div class="filter-group">
                    <label>Период</label>
                    <select name="view" onchange="this.form.submit()">
                        <option value="today" {{ $viewType == 'today' ? 'selected' : '' }}>Сегодня</option>
                        <option value="week" {{ $viewType == 'week' ? 'selected' : '' }}>Неделя</option>
                        <option value="month" {{ $viewType == 'month' ? 'selected' : '' }}>Месяц</option>
                        <option value="calendar" {{ $viewType == 'calendar' ? 'selected' : '' }}>Календарь</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Статус</label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">Все</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершено</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                    </select>
                </div>
                <div class="filter-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" style="padding: 10px 20px; background: var(--gold); border: none; border-radius: 5px; color: var(--chocolate); font-weight: 600; cursor: pointer; width: 100%;">Найти</button>
                </div>
            </form>
            
            <!-- Переключение вида -->
            <div class="view-toggle">
                <button class="view-btn active" id="list-view-btn" onclick="showListView()">📋 Список</button>
                <button class="view-btn" id="calendar-view-btn" onclick="showCalendarView()">📅 Календарь</button>
            </div>
            
            <!-- Календарный вид -->
            <div id="calendar-view">
                <div id="calendar-container" style="min-height: 500px;"></div>
            </div>
            
            <!-- Список записей -->
            <div id="list-view">
                @forelse($bookings as $booking)
                <div class="schedule-item">
                    <div class="schedule-time">{{ $booking->start_time->format('H:i') }}</div>
                    <div class="client-info">
                        <h4>{{ $booking->client->name ?? 'Гость' }}</h4>
                        <p>{{ $booking->service->name ?? 'Услуга удалена' }}</p>
                        <p style="margin-top: 5px;">
                            @php
                                $statusClass = match($booking->status) {
                                    'pending' => 'status-pending',
                                    'confirmed' => 'status-confirmed',
                                    'completed' => 'status-completed',
                                    'cancelled' => 'status-cancelled',
                                    default => ''
                                };
                                $statusLabel = match($booking->status) {
                                    'pending' => 'Ожидает',
                                    'confirmed' => 'Подтверждено',
                                    'completed' => 'Завершено',
                                    'cancelled' => 'Отменено',
                                    default => $booking->status
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </p>
                    </div>
                    <div class="action-btns">
                        @if($booking->status != 'completed')
                        <button class="btn-action btn-complete" onclick="updateStatus({{ $booking->id }}, 'completed')">Завершить</button>
                        @endif
                        @if($booking->status == 'pending')
                        <button class="btn-action" onclick="updateStatus({{ $booking->id }}, 'confirmed')">Подтвердить</button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="schedule-item">
                    <p style="color: #888; text-align: center; width: 100%;">Записей не найдено</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/ru.js'></script>
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
        
        // Переключение вида
        function showListView() {
            document.getElementById('list-view').style.display = 'block';
            document.getElementById('calendar-view').style.display = 'none';
            document.getElementById('list-view-btn').classList.add('active');
            document.getElementById('calendar-view-btn').classList.remove('active');
        }
        
        function showCalendarView() {
            document.getElementById('list-view').style.display = 'none';
            document.getElementById('calendar-view').style.display = 'block';
            document.getElementById('list-view-btn').classList.remove('active');
            document.getElementById('calendar-view-btn').classList.add('active');
            initCalendar();
        }
        
        // Инициализация календаря
        let calendarInitialized = false;
        function initCalendar() {
            if (calendarInitialized) return;
            calendarInitialized = true;
            
            const calendarEl = document.getElementById('calendar-container');
            if (!calendarEl) return;
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'ru',
                initialView: window.innerWidth <= 768 ? 'timeGridDay' : 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: window.innerWidth <= 768 ? 'timeGridDay' : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach($bookings as $booking)
                    {
                        title: '{{ $booking->client->name ?? "Гость" }} - {{ $booking->service->name ?? "Услуга" }}',
                        start: '{{ $booking->start_time->format('Y-m-d\TH:i:s') }}',
                        end: '{{ $booking->end_time->format('Y-m-d\TH:i:s') }}',
                        color: @php
                            echo match($booking->status) {
                                'confirmed' => "'#2e7d32'",
                                'pending' => "'#ef6c00'",
                                'cancelled' => "'#c62828'",
                                'completed' => "'#1565c0'",
                                default => "'#999'"
                            };
                        @endphp,
                        extendedProps: {
                            bookingId: {{ $booking->id }},
                            status: '{{ $booking->status }}'
                        }
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    const bookingId = info.event.extendedProps.bookingId;
                    const status = info.event.extendedProps.status;
                    if (confirm('Изменить статус записи #' + bookingId + '?')) {
                        updateStatus(bookingId, status === 'pending' ? 'confirmed' : 'completed');
                    }
                },
                height: 'auto'
            });
            
            calendar.render();
        }
        
        // Обновление статуса
        async function updateStatus(bookingId, status) {
            try {
                const response = await fetch(`{{ url('/specialist/bookings') }}/${bookingId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });

                if (response.ok) {
                    location.reload();
                } else {
                    const result = await response.json();
                    alert(result.message || 'Ошибка при обновлении статуса');
                }
            } catch (error) {
                alert('Ошибка сети');
            }
        }
        
        // Автоматически показываем календарь если выбран view=calendar
        @if($viewType == 'calendar')
        document.addEventListener('DOMContentLoaded', function() {
            showCalendarView();
        });
        @endif
    </script>
</body>
</html>
