@extends('layouts.app')

@section('title', 'Шоколад — Салон красоты в Сергиевом Посаде')

@section('styles')
<style>
    /* Hero Section */
    .hero {
        height: 100vh;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1560750588-73207b1ef5b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--white);
        padding-top: 80px;
    }

    .hero-content h1 {
        font-size: 64px;
        margin-bottom: 20px;
        animation: fadeInUp 1s ease;
    }

    .hero-content p {
        font-size: 18px;
        margin-bottom: 30px;
        font-weight: 300;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        animation: fadeInUp 1.2s ease;
    }

    .btn {
        display: inline-block;
        padding: 15px 40px;
        background-color: var(--gold);
        color: var(--chocolate);
        text-decoration: none;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 2px;
        transition: all 0.3s ease;
        animation: fadeInUp 1.4s ease;
    }

    .btn:hover {
        background-color: var(--gold-light);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    /* Sections */
    section {
        padding: 100px 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-title h2 {
        font-size: 42px;
        color: var(--chocolate);
        margin-bottom: 15px;
    }

    .section-title .divider {
        width: 80px;
        height: 3px;
        background-color: var(--gold);
        margin: 0 auto;
    }

    /* Services Preview */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .service-card {
        background: var(--white);
        padding: 40px;
        text-align: center;
        border-radius: 5px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-bottom: 4px solid transparent;
    }

    .service-card:hover {
        transform: translateY(-10px);
        border-bottom-color: var(--gold);
    }

    .service-card h3 {
        font-size: 24px;
        margin-bottom: 15px;
        color: var(--chocolate);
    }

    .service-card p {
        color: var(--text-light);
        font-size: 14px;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Искусство Красоты</h1>
            <p>Погрузитесь в атмосферу роскоши и заботы. Мы создаем образы, которые вдохновляют.</p>
            <a href="{{ route('booking') }}" class="btn">Записаться онлайн</a>
        </div>
    </div>
</section>

<section id="welcome">
    <div class="container">
        <div class="section-title">
            <h2>Добро пожаловать в Шоколад</h2>
            <div class="divider"></div>
        </div>
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <p style="font-size: 18px; color: var(--text-light);">Салон красоты «Шоколад» — это место, где профессионализм встречается с уютом. Мы предлагаем полный спектр услуг для вашей красоты и здоровья, используя только премиальную косметику и современное оборудование.</p>
        </div>
    </div>
</section>

<section id="services-preview" style="background-color: #f9f9f9;">
    <div class="container">
        <div class="section-title">
            <h2>Наши Направления</h2>
            <div class="divider"></div>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <h3>Парикмахерский зал</h3>
                <p>Сложные окрашивания, стрижки, уходы за волосами от ведущих стилистов.</p>
            </div>
            <div class="service-card">
                <h3>Ногтевой сервис</h3>
                <p>Маникюр, педикюр, наращивание и дизайн ногтей любой сложности.</p>
            </div>
            <div class="service-card">
                <h3>Косметология</h3>
                <p>Уходы за лицом, чистки, пилинги и аппаратные процедуры для вашей кожи.</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('services') }}" class="btn" style="background: transparent; border: 2px solid var(--gold); color: var(--chocolate);">Смотреть все услуги</a>
        </div>
    </div>
</section>
@endsection
