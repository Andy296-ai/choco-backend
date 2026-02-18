@extends('layouts.app')

@section('title', 'Галерея — Шоколад')

@section('breadcrumbs')
<div class="container">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Главная</a>
        <span>/</span>
        <span>Галерея</span>
    </div>
</div>
@endsection

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
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    .gallery-item {
        background: var(--white);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .gallery-item-image {
        position: relative;
        width: 100%;
        aspect-ratio: 1/1;
        overflow: hidden;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .gallery-item-content {
        padding: 20px;
    }

    .gallery-item-title {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        color: var(--chocolate);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .gallery-item-description {
        color: var(--text-light);
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .gallery-item-author {
        font-size: 12px;
        color: var(--gold);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .empty-gallery {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-light);
    }

    .empty-gallery-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Галерея работ</h1>
        <p>Портфолио наших специалистов</p>
    </div>
</div>

<main class="gallery-section">
    <div class="container">
        @if($portfolioItems->count() > 0)
            <div class="gallery-grid">
                @foreach($portfolioItems as $item)
                    <div class="gallery-item">
                        <div class="gallery-item-image">
                            <img src="{{ $item->image_path }}" alt="{{ $item->title ?? 'Работа специалиста' }}">
                        </div>
                        <div class="gallery-item-content">
                            @if($item->title)
                                <div class="gallery-item-title">{{ $item->title }}</div>
                            @endif
                            @if($item->description)
                                <div class="gallery-item-description">{{ $item->description }}</div>
                            @endif
                            <div class="gallery-item-author">
                                Мастер: {{ $item->user->name ?? 'Неизвестный мастер' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-gallery">
                <div class="empty-gallery-icon">🖼️</div>
                <h3>Галерея пока пуста</h3>
                <p>Наши специалисты скоро добавят свои работы</p>
            </div>
        @endif
    </div>
</main>
@endsection
