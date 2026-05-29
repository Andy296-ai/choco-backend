@extends('layouts.app')

@section('title', 'Мой кабинет — Шоколад')

@section('styles')
    @include('client.partials.area-styles')
    <style>
        /* ── Профильная шапка ── */
        .client-profile-header {
            display: flex;
            align-items: center;
            gap: 24px;
            padding: 28px 32px;
            background: linear-gradient(135deg, var(--chocolate) 0%, #5D4037 100%);
            border-radius: 12px;
            margin-bottom: 28px;
            color: white;
        }

        .client-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid var(--gold);
            flex-shrink: 0;
            object-fit: cover;
            background: rgba(212,175,55,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            color: var(--gold);
            overflow: hidden;
        }

        .client-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .client-profile-info h2 {
            margin: 0 0 6px;
            font-family: 'Playfair Display', serif;
            font-size: 22px;
        }

        .client-profile-info p {
            margin: 0;
            font-size: 14px;
            opacity: 0.75;
        }

        /* ── Быстрые статы ── */
        .client-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .client-stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border-top: 3px solid var(--gold);
        }

        .client-stat-card .stat-num {
            font-size: 28px;
            font-weight: 700;
            color: var(--chocolate);
            display: block;
        }

        .client-stat-card .stat-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* ── Активная запись ── */
        .next-booking-card {
            background: var(--cream);
            border-left: 4px solid var(--gold);
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .next-booking-card h4 {
            margin: 0 0 6px;
            font-family: 'Playfair Display', serif;
            color: var(--chocolate);
            font-size: 16px;
        }

        .next-booking-card p { margin: 3px 0; font-size: 13px; color: #555; }

        .next-booking-tag {
            background: var(--gold);
            color: var(--chocolate);
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 12px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 8px;
        }

        /* ── Две колонки ── */
        .dashboard-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        .dash-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .dash-card h3 {
            font-family: 'Playfair Display', serif;
            color: var(--chocolate);
            margin: 0 0 16px;
            font-size: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--gold);
        }

        .profile-form .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .profile-form .form-group { margin-bottom: 14px; }

        .profile-form label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .profile-form input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .profile-form input:focus { border-color: var(--gold); }

        .profile-form .btn-save {
            background: var(--chocolate);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .profile-form .btn-save:hover { background: var(--gold); color: var(--chocolate); }

        .quick-links { display: flex; flex-direction: column; gap: 10px; }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #f9f9f9;
            border-radius: 8px;
            text-decoration: none;
            color: var(--chocolate);
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s, transform 0.15s;
            border-left: 3px solid transparent;
        }

        .quick-link:hover { background: var(--cream); border-left-color: var(--gold); transform: translateX(4px); }

        .quick-link-icon { font-size: 20px; width: 28px; text-align: center; }

        @media (max-width: 768px) {
            .client-profile-header { flex-direction: column; text-align: center; padding: 20px; }
            .client-stats { grid-template-columns: repeat(3, 1fr); }
            .dashboard-cols { grid-template-columns: 1fr; }
            .profile-form .form-row { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .client-stats { grid-template-columns: 1fr; }
            .client-stat-card { display: flex; align-items: center; gap: 16px; text-align: left; }
            .client-stat-card .stat-num { font-size: 22px; }
        }
    </style>
@endsection

@section('content')
@php
    $client = auth('client')->user();
    $allBookings    = $bookings->getCollection();
    $upcoming       = $allBookings->whereIn('status', ['pending', 'confirmed'])->sortBy('start_time')->first();
    $totalCount     = $bookings->total();
    $completedCount = \App\Models\Booking::where('client_id', $client->id)->where('status', 'completed')->count();
    $cancelledCount = \App\Models\Booking::where('client_id', $client->id)->where('status', 'cancelled')->count();
@endphp

<div class="page-header client-page-header">
    <div class="container">
        <h1>Мой кабинет</h1>
        <p class="client-page-subtitle">Добро пожаловать, {{ $client->name }}!</p>
    </div>
</div>

<div class="container client-area-main">

    {{-- Профильная шапка с аватаром --}}
    <div class="client-profile-header">
        <div class="client-avatar">
            @if($client->telegram_photo_url)
                <img src="{{ $client->telegram_photo_url }}" alt="{{ $client->name }}">
            @else
                {{ mb_strtoupper(mb_substr($client->name, 0, 1)) }}
            @endif
        </div>
        <div class="client-profile-info">
            <h2>{{ $client->name }}</h2>
            <p>{{ $client->phone ?? 'Телефон не указан' }}</p>
            @if($client->telegram_username)
                <p>@{{ $client->telegram_username }}</p>
            @endif
            @if($client->discount > 0)
                <p style="color: var(--gold); font-weight: 600; margin-top: 8px;">Скидка: {{ $client->discount }}%</p>
            @endif
        </div>
    </div>

    {{-- Быстрая статистика --}}
    <div class="client-stats">
        <div class="client-stat-card">
            <span class="stat-num">{{ $totalCount }}</span>
            <div class="stat-label">Всего записей</div>
        </div>
        <div class="client-stat-card">
            <span class="stat-num">{{ $completedCount }}</span>
            <div class="stat-label">Завершено</div>
        </div>
        <div class="client-stat-card">
            <span class="stat-num">{{ $cancelledCount }}</span>
            <div class="stat-label">Отменено</div>
        </div>
    </div>

    {{-- Ближайшая запись --}}
    @if($upcoming)
    <div class="next-booking-card">
        <div>
            <span class="next-booking-tag">Ближайшая запись</span>
            <h4>{{ $upcoming->service->name }}</h4>
            <p>Мастер: {{ $upcoming->specialist->name }}</p>
            <p>{{ $upcoming->start_time->format('d.m.Y') }} в {{ $upcoming->start_time->format('H:i') }}</p>
            <p>{{ $upcoming->salon->name }}</p>
        </div>
        <div>
            @if($upcoming->status === 'pending')
                <span style="background:#fff3e0; color:#ef6c00; padding:4px 12px; border-radius:12px; font-size:11px; font-weight:700; text-transform:uppercase;">Ожидает</span>
            @elseif($upcoming->status === 'confirmed')
                <span style="background:#e8f5e9; color:#2e7d32; padding:4px 12px; border-radius:12px; font-size:11px; font-weight:700; text-transform:uppercase;">Подтверждено</span>
            @endif
        </div>
    </div>
    @endif

    {{-- Профиль + Быстрые действия --}}
    <div class="dashboard-cols">
        {{-- Редактировать профиль --}}
        <div class="dash-card">
            <h3>Мои данные</h3>
            @if(session('success'))
                <div style="background:#e8f5e9; color:#2e7d32; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#ffebee; color:#c62828; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px;">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label>Имя</label>
                        <input type="text" name="name" value="{{ old('name', $client->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" placeholder="+7...">
                    </div>
                </div>
                <button type="submit" class="btn-save">Сохранить</button>
            </form>
        </div>

        {{-- Быстрые ссылки --}}
        <div class="dash-card">
            <h3>Быстрый доступ</h3>
            <div class="quick-links">
                <a href="{{ route('client.bookings') }}" class="quick-link">
                    <span class="quick-link-icon">📋</span>
                    История записей
                </a>
                <a href="{{ route('booking') }}" class="quick-link">
                    <span class="quick-link-icon">➕</span>
                    Новая запись
                </a>
                <a href="{{ route('gallery') }}" class="quick-link">
                    <span class="quick-link-icon">🖼️</span>
                    Галерея работ
                </a>
                <a href="{{ route('services') }}" class="quick-link">
                    <span class="quick-link-icon">💅</span>
                    Наши услуги
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
