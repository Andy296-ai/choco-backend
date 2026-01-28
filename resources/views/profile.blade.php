@extends('layouts.app')

@section('title', 'Личный кабинет — Шоколад')

@section('styles')
<style>
    .page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .profile-container {
        max-width: 900px;
        margin: 50px auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .auth-card {
        background: var(--white);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .auth-card h2 {
        font-family: 'Playfair Display', serif;
        color: var(--chocolate);
        margin-bottom: 25px;
        text-align: center;
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
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: inherit;
    }

    .btn-auth {
        width: 100%;
        padding: 15px;
        background-color: var(--chocolate);
        color: var(--white);
        border: none;
        border-radius: 5px;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-auth:hover {
        background-color: var(--gold);
        color: var(--chocolate);
    }

    .btn-telegram {
        background-color: #0088cc;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-transform: none;
    }

    .btn-telegram:hover {
        background-color: #0077b5;
    }

    .telegram-icon {
        font-size: 24px;
    }

    .divider {
        text-align: center;
        margin: 20px 0;
        color: #999;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #ddd;
    }

    .divider span {
        background: var(--white);
        padding: 0 15px;
        position: relative;
        z-index: 1;
    }

    .dashboard-container {
        max-width: 1000px;
        margin: 50px auto;
        background: var(--white);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #f5f5f5;
        padding-bottom: 20px;
    }

    .booking-history {
        margin-top: 30px;
    }

    .booking-item {
        display: flex;
        justify-content: space-between;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .booking-info h4 {
        color: var(--chocolate);
        margin-bottom: 5px;
    }

    .booking-status {
        font-weight: 600;
        color: var(--gold);
    }

    @media (max-width: 768px) {
        .profile-container { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Личный кабинет</h1>
    </div>
</div>

<div class="container">
    @if(!Session::has('client'))
        <div style="max-width: 500px; margin: 50px auto; text-align: center;">
            <div class="auth-card">
                <h2>Вход в кабинет</h2>
                <p style="margin-bottom: 30px; color: var(--text-light);">Для доступа к личному кабинету, пожалуйста, авторизуйтесь через Telegram. Это быстро и безопасно.</p>
                
                <!-- Telegram Login Widget -->
                <div style="display: flex; justify-content: center;">
                    <script async src="https://telegram.org/js/telegram-widget.js?22" 
                            data-telegram-login="choco_bot"
                            data-size="large"
                            data-auth-url="{{ route('auth.telegram') }}"
                            data-request-access="write"
                            data-radius="5"></script>
                </div>

                <p style="margin-top: 30px; font-size: 14px; color: #888;">
                    Нажимая кнопку входа, вы соглашаетесь с правилами нашего сервиса.
                </p>
            </div>
        </div>
    @else
        <!-- Client Dashboard -->
        <div class="dashboard-container">
            <div class="dashboard-header">
                <div>
                    <h2>Здравствуйте, {{ Session::get('client.name') }}!</h2>
                    <p style="color: #888;">Добро пожаловать в ваш личный кабинет</p>
                    @if(Session::get('client.telegram_id'))
                        <p style="color: #0088cc; margin-top: 5px;">
                            <span class="telegram-icon">✈️</span> 
                            Подключён к аккаунту Telegram: @{{ Session::get('client.telegram_username') }}
                        </p>
                    @endif
                </div>
                <form action="{{ route('client.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-auth" style="width: auto; padding: 10px 20px;">Выйти</button>
                </form>
            </div>

            <div class="booking-history">
                <h3>Мои записи</h3>
                <div class="booking-item">
                    <div class="booking-info">
                        <h4>Сложное окрашивание</h4>
                        <p>26 января 2026, 10:00 — Мастер Елена К.</p>
                        <p style="font-size: 12px; color: #888;">Салон на Вознесенской, 46</p>
                    </div>
                    <div class="booking-status">Предстоящая</div>
                </div>
                
                <div class="booking-item" style="opacity: 0.7;">
                    <div class="booking-info">
                        <h4>Маникюр + гель-лак</h4>
                        <p>15 декабря 2025, 14:00 — Мастер Ольга С.</p>
                    </div>
                    <div class="booking-status" style="color: #888;">Завершена</div>
                </div>
            </div>

            <div style="margin-top: 40px; padding: 20px; background: var(--cream); border-radius: 5px;">
                <h4>Ваша персональная скидка: <span style="color: var(--gold); font-size: 24px;">10%</span></h4>
                <p style="font-size: 14px; color: var(--chocolate-light);">Скидка применяется автоматически при оплате услуг в салоне.</p>
            </div>
        </div>
    @endif
</div>
@endsection