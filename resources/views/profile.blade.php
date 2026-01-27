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
        <div class="profile-container">
            <!-- Login Form -->
            <div class="auth-card">
                <h2>Вход</h2>
                <form action="{{ route('client.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email или Телефон</label>
                        <input type="text" name="login" required>
                    </div>
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-auth">Войти</button>
                </form>
            </div>

            <!-- Registration Form -->
            <div class="auth-card">
                <h2>Регистрация</h2>
                <form action="{{ route('client.register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Ваше имя</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-auth">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    @else
        <!-- Client Dashboard -->
        <div class="dashboard-container">
            <div class="dashboard-header">
                <div>
                    <h2>Здравствуйте, {{ Session::get('client.name') }}!</h2>
                    <p style="color: #888;">Добро пожаловать в ваш личный кабинет</p>
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
