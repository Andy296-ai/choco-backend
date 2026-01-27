@extends('layouts.app')

@section('title', 'Галерея — Шоколад')

@section('styles')
<style>
    .page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .page-header h1 { font-size: 48px; margin-bottom: 10px; }

    .gallery-section { padding: 80px 0; }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 5px;
        aspect-ratio: 1/1;
        cursor: pointer;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(62, 39, 35, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: var(--white);
        font-family: 'Playfair Display', serif;
        font-size: 20px;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Галерея работ</h1>
        <p>Вдохновение в каждой детали</p>
    </div>
</div>

<main class="gallery-section">
    <div class="container">
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Работа 1">
                <div class="gallery-overlay">Сложное окрашивание</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1604654894610-df490c81726a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Работа 2">
                <div class="gallery-overlay">Маникюр</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Работа 3">
                <div class="gallery-overlay">Уход за лицом</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Работа 4">
                <div class="gallery-overlay">Стрижка</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1600948836101-f9ffda59d250?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Работа 5">
                <div class="gallery-overlay">Педикюр</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Работа 6">
                <div class="gallery-overlay">Косметология</div>
            </div>
        </div>
    </div>
</main>
@endsection
