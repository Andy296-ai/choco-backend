@extends('layouts.app')

@section('title', 'Услуги и Цены — Шоколад')

@section('styles')
<style>
    .page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .page-header h1 { font-size: 48px; margin-bottom: 10px; }

    .services-section { padding: 80px 0; }
    .service-category { margin-bottom: 60px; }
    .category-title {
        font-size: 32px;
        color: var(--chocolate);
        margin-bottom: 30px;
        border-bottom: 2px solid var(--gold);
        display: inline-block;
        padding-bottom: 5px;
    }

    .price-list { list-style: none; }
    .price-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px dashed #ccc;
        transition: background 0.3s ease;
    }
    .price-item:hover { background: rgba(212, 175, 55, 0.05); }
    .service-name { font-weight: 400; }
    .service-price { font-weight: 600; color: var(--chocolate); }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Услуги и Цены</h1>
        <p>Ваш путь к совершенству начинается здесь</p>
    </div>
</div>

<main class="services-section">
    <div class="container">
        <div class="service-category">
            <h2 class="category-title">Парикмахерские услуги</h2>
            <ul class="price-list">
                <li class="price-item"><span class="service-name">Женская стрижка</span> <span class="service-price">от 1 500 ₽</span></li>
                <li class="price-item"><span class="service-name">Мужская стрижка</span> <span class="service-price">от 1 000 ₽</span></li>
                <li class="price-item"><span class="service-name">Окрашивание в один тон</span> <span class="service-price">от 3 500 ₽</span></li>
                <li class="price-item"><span class="service-name">Сложное окрашивание (Airtouch, Balayage)</span> <span class="service-price">от 7 000 ₽</span></li>
                <li class="price-item"><span class="service-name">Укладка вечерняя</span> <span class="service-price">от 2 500 ₽</span></li>
            </ul>
        </div>

        <div class="service-category">
            <h2 class="category-title">Ногтевой сервис</h2>
            <ul class="price-list">
                <li class="price-item"><span class="service-name">Маникюр классический</span> <span class="service-price">800 ₽</span></li>
                <li class="price-item"><span class="service-name">Маникюр с покрытием гель-лак</span> <span class="service-price">1 800 ₽</span></li>
                <li class="price-item"><span class="service-name">Педикюр классический</span> <span class="service-price">1 800 ₽</span></li>
                <li class="price-item"><span class="service-name">Наращивание ногтей</span> <span class="service-price">от 3 000 ₽</span></li>
            </ul>
        </div>

        <div class="service-category">
            <h2 class="category-title">Косметология</h2>
            <ul class="price-list">
                <li class="price-item"><span class="service-name">Комбинированная чистка лица</span> <span class="service-price">3 000 ₽</span></li>
                <li class="price-item"><span class="service-name">Пилинг (по типу кожи)</span> <span class="service-price">от 2 500 ₽</span></li>
                <li class="price-item"><span class="service-name">Массаж лица</span> <span class="service-price">1 500 ₽</span></li>
                <li class="price-item"><span class="service-name">Архитектура бровей</span> <span class="service-price">1 000 ₽</span></li>
            </ul>
        </div>
    </div>
</main>
@endsection
