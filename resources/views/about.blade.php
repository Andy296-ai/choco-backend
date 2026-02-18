@extends('layouts.app')

@section('title', 'О нас — Шоколад')

@section('styles')
<style>
    .page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .page-header h1 { font-size: 48px; margin-bottom: 10px; }

    .about-section { padding: 80px 0; }
    .about-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }

    .about-text h2 { font-size: 36px; color: var(--chocolate); margin-bottom: 20px; }
    .about-text p { margin-bottom: 20px; color: var(--text-light); }

    .about-image img {
        width: 100%;
        border-radius: 5px;
        box-shadow: 20px 20px 0 var(--gold);
    }

    .stats {
        display: flex;
        justify-content: space-around;
        margin-top: 80px;
        text-align: center;
    }

    .stat-item h3 { font-size: 48px; color: var(--gold); margin-bottom: 5px; }
    .stat-item p { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }

    @media (max-width: 768px) {
        .about-content { grid-template-columns: 1fr; }
        .about-image { order: -1; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>О нашем салоне</h1>
        <p>История создания и наша философия</p>
    </div>
</div>

<main class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>Более 10 лет совершенства</h2>
                <p>Салон красоты «Шоколад» был основан с одной простой целью: создать пространство, где каждый клиент сможет почувствовать себя особенным. Мы верим, что красота — это не только внешнее преображение, но и внутреннее состояние гармонии.</p>
                <p>Наша команда состоит из дипломированных специалистов, которые постоянно совершенствуют свои навыки, посещая международные мастер-классы и выставки. Мы используем только проверенные бренды косметики и следим за последними трендами в индустрии красоты.</p>
                <p>В «Шоколаде» мы уделяем внимание каждой детали — от аромата свежесваренного кофе до стерильности инструментов. Ваша безопасность и комфорт — наш приоритет.</p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Интерьер салона">
            </div>
        </div>

        <div class="stats">
            <div class="stat-item">
                <h3>10+</h3>
                <p>Лет опыта</p>
            </div>
            <div class="stat-item">
                <h3>5000+</h3>
                <p>Счастливых клиентов</p>
            </div>
            <div class="stat-item">
                <h3>15</h3>
                <p>Топ-мастеров</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Качество услуг</p>
            </div>
        </div>
    </div>
</main>
@endsection
