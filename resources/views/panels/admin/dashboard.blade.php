<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Панель Администратора — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Shared Modal CSS */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.4); backdrop-filter: blur(8px); display: flex; justify-content: center; align-items: center; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-container { background: #FFFFFF; width: 90%; max-width: 500px; border-radius: 15px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: translateY(20px); transition: all 0.3s ease; border: 1px solid rgba(212, 175, 55, 0.2); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { font-family: 'Playfair Display', serif; color: #3E2723; margin: 0; }
        .close-modal { background: none; border: none; font-size: 24px; color: #888; cursor: pointer; }
        .modal-form .form-group { margin-bottom: 15px; }
        .modal-form label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 5px; color: #3E2723; }
        .modal-form input, .modal-form select, .modal-form textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
        .btn-cancel { background: #f5f5f5; color: #3E2723; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-confirm { background: #D4AF37; color: #3E2723; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }

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
            cursor: pointer;
        }

        .status-confirmed { background: #e8f5e9; color: #2e7d32; }
        .status-pending { background: #fff3e0; color: #ef6c00; }
        .status-cancelled { background: #ffebee; color: #c62828; }
        .status-completed { background: #e3f2fd; color: #1565c0; }
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
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h3>Записи на сегодня ({{ now()->translatedFormat('j F') }}) — {{ $salon->name ?? 'Салон не назначен' }}</h3>
                <button class="logout-btn" style="background: var(--gold); border: none;" data-modal="modal-booking-add">+ Новая запись</button>
            </div>
            <div class="appointment-list">
                @forelse($bookings as $booking)
                <div class="appointment-item">
                    <div class="appointment-time">{{ $booking->start_time->format('H:i') }}</div>
                    <div class="appointment-info">
                        <h4>{{ $booking->user->name }}</h4>
                        <p>{{ $booking->service->name }} — Мастер: {{ $booking->specialist->name }}</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        @php
                            $statusClass = match($booking->status) {
                                'confirmed' => 'status-confirmed',
                                'pending' => 'status-pending',
                                'cancelled' => 'status-cancelled',
                                'completed' => 'status-completed',
                                default => ''
                            };
                            $statusLabel = match($booking->status) {
                                'confirmed' => 'Подтверждено',
                                'pending' => 'Ожидает',
                                'cancelled' => 'Отменено',
                                'completed' => 'Завершено',
                                default => $booking->status
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}" 
                              data-modal="modal-booking-edit" 
                              data-edit="{{ json_encode($booking) }}"
                              data-action="{{ route('admin.bookings.update', $booking->id) }}">
                            {{ $statusLabel }}
                        </span>
                        <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" class="ajax-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color: #f44336; cursor:pointer;" onclick="return confirm('Удалить запись?')">×</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="appointment-item">
                    <p style="color: #888; text-align: center; width: 100%;">На сегодня записей нет</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal-overlay" id="modal-booking-add">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Новая запись</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form action="{{ route('admin.bookings.store') }}" method="POST" class="ajax-form modal-form">
                @csrf
                <div class="form-group">
                    <label>Клиент</label>
                    <select name="user_id" required>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Услуга</label>
                    <select name="service_id" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} — {{ $service->price }} ₽</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Мастер</label>
                    <select name="specialist_id" required>
                        @foreach($masters as $master)
                            <option value="{{ $master->id }}">{{ $master->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Время начала</label>
                    <input type="datetime-local" name="start_time" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="form-group">
                    <label>Статус</label>
                    <select name="status" required>
                        <option value="pending">Ожидает</option>
                        <option value="confirmed" selected>Подтверждено</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Создать</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-booking-edit">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Редактировать запись</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-form modal-form">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Услуга</label>
                    <select name="service_id" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Мастер</label>
                    <select name="specialist_id" required>
                        @foreach($masters as $master)
                            <option value="{{ $master->id }}">{{ $master->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Время начала</label>
                    <input type="datetime-local" name="start_time" required>
                </div>
                <div class="form-group">
                    <label>Статус</label>
                    <select name="status" required>
                        <option value="pending">Ожидает</option>
                        <option value="confirmed">Подтверждено</option>
                        <option value="completed">Завершено</option>
                        <option value="cancelled">Отменено</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById(btn.getAttribute('data-modal'));
                    if (modal) {
                        modal.classList.add('active');
                        if (btn.hasAttribute('data-edit')) {
                            const data = JSON.parse(btn.getAttribute('data-edit'));
                            const form = modal.querySelector('form');
                            form.action = btn.getAttribute('data-action');
                            
                            // Specific date format for datetime-local
                            const startTime = new Date(data.start_time);
                            const formattedDate = startTime.toISOString().slice(0, 16);
                            
                            Object.keys(data).forEach(key => {
                                const input = form.querySelector(`[name="${key}"]`);
                                if (input) {
                                    if (key === 'start_time') input.value = formattedDate;
                                    else input.value = data[key];
                                }
                            });
                        }
                    }
                });
            });

            document.querySelectorAll('.close-modal, .btn-cancel').forEach(btn => {
                btn.addEventListener('click', () => btn.closest('.modal-overlay').classList.remove('active'));
            });

            document.querySelectorAll('.ajax-form').forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const btn = form.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if (response.ok) location.reload();
                        else alert('Ошибка при сохранении');
                    } catch (e) { alert('Ошибка сети'); }
                    btn.disabled = false;
                });
            });
        });
    </script>
</body>
</html>
