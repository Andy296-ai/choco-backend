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
        cursor: zoom-in;
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
        font-family: 'Raleway', sans-serif;
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

    /* ── Lightbox ── */
    #lightbox {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.9);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 20px;
    }
    #lightbox.active { display: flex; }

    #lightbox-img {
        max-width: 90vw;
        max-height: 72vh;
        object-fit: contain;
        border-radius: 6px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        transition: transform 0.3s ease;
    }

    #lightbox-caption {
        margin-top: 18px;
        text-align: center;
        color: rgba(255,255,255,0.92);
        max-width: 560px;
    }

    #lightbox-caption .lb-title {
        font-family: 'Raleway', sans-serif;
        font-size: 20px;
        margin-bottom: 6px;
    }

    #lightbox-caption .lb-desc  { font-size: 14px; opacity: 0.75; line-height: 1.5; }
    #lightbox-caption .lb-author { font-size: 12px; opacity: 0.55; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.5px; }

    #lightbox-close {
        position: absolute; top: 20px; right: 24px;
        background: none; border: none;
        color: white; font-size: 34px; cursor: pointer;
        opacity: 0.7; line-height: 1;
    }
    #lightbox-close:hover { opacity: 1; }

    /* стрелки навигации */
    .lb-nav {
        position: absolute;
        top: 50%; transform: translateY(-50%);
        background: rgba(255,255,255,0.1);
        border: none; color: white;
        font-size: 28px; cursor: pointer;
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .lb-nav:hover { background: rgba(255,255,255,0.25); }
    #lb-prev { left: 16px; }
    #lb-next { right: 16px; }

    /* zoom-overlay при наведении на карточку */
    .gallery-item-image::after {
        content: '🔍';
        position: absolute; inset: 0;
        background: rgba(62,39,35,0.35);
        display: flex; align-items: center; justify-content: center;
        font-size: 32px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .gallery-item:hover .gallery-item-image::after { opacity: 1; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Галерея работ</h1>
        <p>Портфолио наших специалистов</p>
    </div>
</div>

<section class="gallery-section">
    <div class="container">
        @if($portfolioItems->count() > 0)
            <div class="gallery-grid" id="gallery-grid">
                @foreach($portfolioItems as $index => $item)
                    <div class="gallery-item"
                         onclick="openLightbox({{ $index }})"
                         data-img="{{ $item->image_path }}"
                         data-title="{{ $item->title ?? '' }}"
                         data-desc="{{ $item->description ?? '' }}"
                         data-author="{{ $item->user->name ?? 'Неизвестный мастер' }}">
                        <div class="gallery-item-image">
                            <img src="{{ $item->image_path }}" alt="{{ $item->title ?? 'Работа специалиста' }}" loading="lazy">
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
        
        @if($portfolioItems->hasPages())
            <div style="margin-top: 40px; text-align: center;">
                {{ $portfolioItems->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Lightbox -->
<div id="lightbox" onclick="handleLightboxClick(event)">
    <button id="lightbox-close" onclick="closeLightbox()">×</button>
    <button class="lb-nav" id="lb-prev" onclick="event.stopPropagation(); shiftLightbox(-1)">&#8249;</button>
    <img id="lightbox-img" src="" alt="">
    <button class="lb-nav" id="lb-next" onclick="event.stopPropagation(); shiftLightbox(1)">&#8250;</button>
    <div id="lightbox-caption">
        <div id="lb-title"  class="lb-title"></div>
        <div id="lb-desc"   class="lb-desc"></div>
        <div id="lb-author" class="lb-author"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let lbItems = [], lbIndex = 0;

    document.addEventListener('DOMContentLoaded', () => {
        lbItems = Array.from(document.querySelectorAll('#gallery-grid .gallery-item'));
    });

    function openLightbox(index) {
        lbIndex = index;
        renderLightbox();
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function renderLightbox() {
        const el = lbItems[lbIndex];
        if (!el) return;
        document.getElementById('lightbox-img').src = el.dataset.img;
        const tEl = document.getElementById('lb-title');
        const dEl = document.getElementById('lb-desc');
        const aEl = document.getElementById('lb-author');
        tEl.textContent = el.dataset.title;  tEl.style.display = el.dataset.title  ? 'block' : 'none';
        dEl.textContent = el.dataset.desc;   dEl.style.display = el.dataset.desc   ? 'block' : 'none';
        aEl.textContent = 'Мастер: ' + el.dataset.author;
        document.getElementById('lb-prev').style.display = lbIndex > 0 ? 'flex' : 'none';
        document.getElementById('lb-next').style.display = lbIndex < lbItems.length - 1 ? 'flex' : 'none';
    }

    function shiftLightbox(dir) {
        lbIndex = Math.max(0, Math.min(lbItems.length - 1, lbIndex + dir));
        renderLightbox();
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    function handleLightboxClick(e) { if (e.target.id === 'lightbox') closeLightbox(); }

    document.addEventListener('keydown', e => {
        if (!document.getElementById('lightbox').classList.contains('active')) return;
        if (e.key === 'Escape')     closeLightbox();
        if (e.key === 'ArrowLeft')  shiftLightbox(-1);
        if (e.key === 'ArrowRight') shiftLightbox(1);
    });
</script>
@endsection
