@extends('layouts.app')

@section('title', 'Контакты — Шоколад')

@section('styles')
<style>
    .page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .page-header h1 { font-size: 48px; margin-bottom: 10px; }

    .contacts-section { padding: 80px 0; }
    .contacts-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 60px;
    }

    .contact-info h2 { font-size: 32px; color: var(--chocolate); margin-bottom: 30px; }
    .info-item { margin-bottom: 30px; }
    .info-item h4 { font-size: 18px; color: var(--gold); margin-bottom: 5px; }
    .info-item p { color: var(--text-light); }

    .contact-form {
        background: var(--white);
        padding: 40px;
        border-radius: 5px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; margin-bottom: 5px; font-weight: 600; }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 2px;
        font-family: inherit;
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        background-color: var(--gold);
        color: var(--chocolate);
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .btn-submit:hover { background-color: var(--gold-light); }

    .map-container {
        margin-top: 80px;
        height: 400px;
        background-color: #eee;
        border-radius: 5px;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .contacts-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Свяжитесь с нами</h1>
        <p>Мы всегда рады видеть вас в нашем салоне</p>
    </div>
</div>

<main class="contacts-section">
    <div class="container">
        <div class="contacts-grid">
            <div class="contact-info">
                <h2>Наши контакты</h2>
                <div class="info-item">
                    <h4>Адрес</h4>
                    <p>г. Сергиев Посад, ул. Вознесенская, 46</p>
                    <p>г. Сергиев Посад, Проспект Красной Армии, 251А</p>
                </div>
                <div class="info-item">
                    <h4>Телефон</h4>
                    <p>+7 (926) 607-07-07</p>
                </div>
                <div class="info-item">
                    <h4>Email</h4>
                    <p>info@chocolate-sp.ru</p>
                </div>
                <div class="info-item">
                    <h4>Часы работы</h4>
                    <p>Ежедневно: 10:00 — 21:00</p>
                </div>
            </div>

            <div class="contact-form">
                <h3>Записаться онлайн</h3>
                <p style="margin-bottom: 20px; font-size: 14px; color: var(--text-light);">Оставьте заявку, и наш администратор свяжется с вами для подтверждения записи.</p>
                <form>
                    <div class="form-group">
                        <label>Ваше имя</label>
                        <input type="text" placeholder="Введите имя">
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" placeholder="+7 (___) ___-__-__">
                    </div>
                    <div class="form-group">
                        <label>Услуга</label>
                        <input type="text" placeholder="Какая услуга вас интересует?">
                    </div>
                    <div class="form-group">
                        <label>Сообщение (необязательно)</label>
                        <textarea rows="4" placeholder="Ваши пожелания"></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Отправить заявку</button>
                </form>
            </div>
        </div>

        <div class="map-container">
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #888;">
                <p>Карта с расположением салонов</p>
            </div>
        </div>
    </div>
</main>
@endsection
