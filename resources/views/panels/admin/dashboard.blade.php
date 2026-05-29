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

        /* ── Фильтры — адаптивная сетка ── */
        .filter-form-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .filter-field { display: flex; flex-direction: column; min-width: 0; }
        .filter-field--wide { flex: 2; min-width: 180px; }
        .filter-field:not(.filter-field--wide) { flex: 1; min-width: 130px; }

        .filter-label {
            font-size: 12px; font-weight: 600; color: var(--chocolate);
            margin-bottom: 6px; display: block;
        }

        .filter-input {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            outline: none;
            background: white;
            width: 100%;
            box-sizing: border-box;
        }

        .filter-input:focus { border-color: var(--gold); }

        .filter-btns { display: flex; gap: 8px; align-items: flex-end; flex-shrink: 0; }

        .filter-btn-apply {
            padding: 10px 20px;
            background: var(--gold);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 13px;
            color: var(--chocolate);
            white-space: nowrap;
        }

        .filter-btn-reset {
            padding: 10px 16px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #555;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
            white-space: nowrap;
        }

        @media (max-width: 600px) {
            .filter-form-grid { flex-direction: column; }
            .filter-field, .filter-field--wide { width: 100%; flex: none; }
            .filter-btns { width: 100%; }
            .filter-btn-apply, .filter-btn-reset { flex: 1; text-align: center; }
            .appointment-item { flex-wrap: wrap; gap: 8px; }
            .appointment-time { width: auto; }
        }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Панель Администратора</h1>
    </div>

    <!-- Поиск и фильтры -->
    <div class="calendar-container" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-form-grid">
            <div class="filter-field filter-field--wide">
                <label class="filter-label">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Клиент, услуга, мастер..." class="filter-input">
            </div>
            <div class="filter-field">
                <label class="filter-label">Статус</label>
                <select name="status" class="filter-input">
                    <option value="">Все</option>
                    <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Ожидает</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждена</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Выполнена</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                </select>
            </div>
            <div class="filter-field">
                <label class="filter-label">С</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input">
            </div>
            <div class="filter-field">
                <label class="filter-label">По</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input">
            </div>
            <div class="filter-btns">
                <button type="submit" class="filter-btn-apply">Найти</button>
                <a href="{{ route('admin.dashboard') }}" class="filter-btn-reset">Сброс</a>
            </div>
        </form>
    </div>

    <div class="calendar-container">
        <div class="calendar-header">
            <h3>Записи — {{ $salon->name ?? 'Салон не назначен' }}</h3>
            <button class="btn-add" onclick="openModal('modal-booking-add')">+ Новая запись</button>
        </div>

        <div class="appointment-list">
            @forelse($bookings as $booking)
            <div class="appointment-item">
                <div class="appointment-time">
                    <div>{{ $booking->start_time->format('H:i') }}</div>
                    <div style="font-size:10px; color:#aaa; margin-top:2px;">{{ $booking->start_time->format('d.m') }}</div>
                </div>
                <div class="appointment-info">
                    <h4>{{ $booking->client->name ?? 'Гость' }}</h4>
                    <p>{{ $booking->service->name ?? 'Услуга удалена' }} — Мастер: {{ $booking->specialist->name ?? 'Не назначен' }}</p>
                    {{-- Контактные данные клиента --}}
                    @if($booking->client)
                    <p style="margin-top:5px; display:flex; gap:12px; flex-wrap:wrap;">
                        @if($booking->client->phone)
                            <a href="tel:{{ $booking->client->phone }}"
                               style="color:var(--chocolate); font-weight:600; font-size:12px; text-decoration:none; display:inline-flex; align-items:center; gap:3px;">
                                📞 {{ $booking->client->phone }}
                            </a>
                        @endif
                        @if($booking->client->telegram_username)
                            <a href="https://t.me/{{ $booking->client->telegram_username }}" target="_blank"
                               style="color:#0088cc; font-weight:600; font-size:12px; text-decoration:none; display:inline-flex; align-items:center; gap:3px;">
                                ✈ {{ '@'.$booking->client->telegram_username }}
                            </a>
                        @endif
                        @if(!$booking->client->phone && !$booking->client->telegram_username)
                            <span style="font-size:12px; color:#aaa;">Контактов нет</span>
                        @endif
                    </p>
                    @endif
                </div>
                <div style="display: flex; align-items: center; gap: 10px; flex-shrink:0;">
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
                          data-action="{{ route('admin.bookings.update', $booking->id) }}"
                          style="cursor:pointer;" title="Нажмите для редактирования">
                        {{ $statusLabel }}
                    </span>
                    <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none; border:none; color:#f44336; cursor:pointer; font-size:18px; line-height:1; padding:0;" onclick="return confirm('Удалить запись?')" title="Удалить">×</button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center; padding:60px 20px; color:#888;">
                <div style="font-size:48px; margin-bottom:16px; opacity:0.3;">📋</div>
                <h3 style="font-family:'Raleway',serif; color:var(--chocolate); margin-bottom:8px; font-size:18px;">Записей не найдено</h3>
                <p style="font-size:14px; margin:0 0 20px;">Попробуйте изменить параметры поиска или создайте новую запись</p>
                <button class="logout-btn" style="background:var(--gold); border:none; color:var(--chocolate); font-weight:600;" data-modal="modal-booking-add" onclick="openModal('modal-booking-add')">+ Создать запись</button>
            </div>
            @endforelse

            <div class="choco-pagination-wrap">{{ $bookings->links() }}</div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById(btn.getAttribute('data-modal'));
                    if (!modal) return;
                    modal.classList.add('active');
                    if (btn.hasAttribute('data-edit')) {
                        const data = JSON.parse(btn.getAttribute('data-edit'));
                        const form = modal.querySelector('form');
                        form.action = btn.getAttribute('data-action');
                        const startTime = new Date(data.start_time);
                        const formattedDate = startTime.toISOString().slice(0, 16);
                        Object.keys(data).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) input.value = key === 'start_time' ? formattedDate : (data[key] ?? '');
                        });
                    }
                });
            });
        });
    </script>
@endsection

