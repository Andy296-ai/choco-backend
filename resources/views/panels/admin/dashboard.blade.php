@extends('layouts.admin')

@section('title', 'Панель Администратора — Шоколад')

@section('styles')
    <style>
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
    <!-- Подключение FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />
@endsection

@section('content')
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

    <!-- Поиск и фильтры -->
    <div class="calendar-container" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('admin.dashboard') }}" style="display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr auto; gap: 15px; align-items: flex-end;">
            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 600; color: var(--chocolate); margin-bottom: 8px; display: block;">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Клиент, услуга, мастер..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px; outline: none;">
            </div>
            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 600; color: var(--chocolate); margin-bottom: 8px; display: block;">Статус</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px; outline: none; background: white;">
                    <option value="">Все статусы</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждена</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Выполнена</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 600; color: var(--chocolate); margin-bottom: 8px; display: block;">С</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px; outline: none;">
            </div>
            <div class="form-group" style="margin: 0;">
                <label style="font-size: 13px; font-weight: 600; color: var(--chocolate); margin-bottom: 8px; display: block;">По</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px; outline: none;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="padding: 12px 25px; background: var(--gold); border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: var(--chocolate); transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">Найти</button>
                <a href="{{ route('admin.dashboard') }}" style="padding: 12px 20px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; text-decoration: none; color: #555; display: inline-block; font-weight: 600; transition: background 0.3s ease;">Сброс</a>
            </div>
        </form>
    </div>

    <div class="calendar-container">
        <div class="calendar-header">
            <h3>Записи — {{ $salon->name ?? 'Салон не назначен' }}</h3>
            <div style="display: flex; gap: 10px; align-items: center;">
                <button class="logout-btn" id="view-toggle" style="background: var(--chocolate); color: white; border: none;">📅 Календарь</button>
                <button class="logout-btn" style="background: var(--gold); border: none;" data-modal="modal-booking-add">+ Новая запись</button>
            </div>
        </div>
        
        <!-- Календарный вид (скрыт по умолчанию) -->
        <div id="calendar-view" style="display: none;">
            <div id="calendar-container" style="min-height: 500px;"></div>
        </div>
        
        <!-- Список записей -->
        <div id="list-view" class="appointment-list">
            @forelse($bookings as $booking)
            <div class="appointment-item">
                <div class="appointment-time">{{ $booking->start_time->format('H:i') }}</div>
                <div class="appointment-info">
                    <h4>{{ $booking->client->name ?? 'Гость' }}</h4>
                    <p>{{ $booking->service->name ?? 'Услуга удалена' }} — Мастер: {{ $booking->specialist->name ?? 'Не назначен' }}</p>
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
            
            <!-- Пагинация -->
            @if($bookings->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                @if($bookings->onFirstPage())
                    <span style="padding: 8px 15px; background: #f5f5f5; border-radius: 5px; color: #999;">← Назад</span>
                @else
                    <a href="{{ $bookings->previousPageUrl() }}" style="padding: 8px 15px; background: var(--gold); border-radius: 5px; color: var(--chocolate); text-decoration: none;">← Назад</a>
                @endif
                
                <span style="padding: 8px 15px; background: #f5f5f5; border-radius: 5px;">
                    Страница {{ $bookings->currentPage() }} из {{ $bookings->lastPage() }}
                </span>
                
                @if($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}" style="padding: 8px 15px; background: var(--gold); border-radius: 5px; color: var(--chocolate); text-decoration: none;">Вперёд →</a>
                @else
                    <span style="padding: 8px 15px; background: #f5f5f5; border-radius: 5px; color: #999;">Вперёд →</span>
                @endif
            </div>
            @endif
        </div>
    </div>
@endsection

@section('modals')
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
                    <select name="client_id" required>
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
@endsection

@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/ru.js'></script>
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

            // Переключение вида: список/календарь
            const viewToggle = document.getElementById('view-toggle');
            const calendarView = document.getElementById('calendar-view');
            const listView = document.getElementById('list-view');
            let isCalendarView = false;
            
            if (viewToggle) {
                viewToggle.addEventListener('click', function() {
                    isCalendarView = !isCalendarView;
                    if (isCalendarView) {
                        calendarView.style.display = 'block';
                        listView.style.display = 'none';
                        viewToggle.textContent = '📋 Список';
                        initCalendar();
                    } else {
                        calendarView.style.display = 'none';
                        listView.style.display = 'block';
                        viewToggle.textContent = '📅 Календарь';
                    }
                });
            }
            
            // Инициализация календаря
            function initCalendar() {
                const calendarEl = document.getElementById('calendar-container');
                if (!calendarEl || calendarEl.calendar) return; // Уже инициализирован
                
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'ru',
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
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
                        alert('Запись #' + bookingId);
                    }
                });
                
                calendar.render();
                calendarEl.calendar = calendar;
            }
        });
    </script>
@endsection

