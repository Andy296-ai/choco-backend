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
        grid-template-columns: 1fr 2fr;
        gap: 60px;
    }

    .contact-info h2 { font-size: 32px; color: var(--chocolate); margin-bottom: 30px; }
    
    .salon-selector {
        background: var(--white);
        border: 1px solid #eee;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .salon-item {
        padding: 20px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .salon-item:last-child { border-bottom: none; }
    
    .salon-item:hover { background-color: #f9f9f9; }
    
    .salon-item.active {
        background-color: var(--cream);
        border-left: 4px solid var(--gold);
    }

    .salon-item h4 { margin: 0 0 5px; color: var(--chocolate); }
    .salon-item p { margin: 0; font-size: 14px; color: #666; }

    .map-container {
        height: 600px;
        background-color: #eee;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
    }

    @media (max-width: 900px) {
        .contacts-grid { grid-template-columns: 1fr; }
        .map-container { height: 400px; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Наши салоны</h1>
        <p>Выберите удобный для вас салон на карте</p>
    </div>
</div>

<main class="contacts-section">
    <div class="container">
        <div class="contacts-grid">
            <div class="contact-info">
                <h2>Выберите салон</h2>
                <div class="salon-selector">
                    @foreach($salons as $index => $salon)
                    <div class="salon-item {{ $index === 0 ? 'active' : '' }}" 
                         onclick="selectSalon({{ $index }}, {{ $salon->latitude ?? 56.3075 }}, {{ $salon->longitude ?? 38.1345 }})"
                         data-index="{{ $index }}">
                        <h4>{{ $salon->name }}</h4>
                        <p>{{ $salon->address }}</p>
                        <p style="margin-top: 5px; color: var(--gold);">{{ $salon->phone }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="info-item">
                    <h4>Единый телефон</h4>
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

            <div class="map-container" id="map">
                <!-- Yandex Map will be rendered here -->
            </div>
        </div>
    </div>
</main>

<script src="https://api-maps.yandex.ru/2.1/?apikey=e077a285-b9f1-4bda-916c-e4cc55589140&lang=ru_RU" type="text/javascript"></script>
<script>
    let myMap;
    // Pass salons data to JS
    const salons = @json($salons);

    ymaps.ready(init);

    function init() {
        // Default to first salon or center of Sergiev Posad
        const firstSalon = salons[0] || { latitude: 56.3075, longitude: 38.1345 };
        
        myMap = new ymaps.Map("map", {
            center: [firstSalon.latitude || 56.3075, firstSalon.longitude || 38.1345],
            zoom: 14,
            controls: ['zoomControl', 'fullscreenControl']
        });

        salons.forEach((salon, index) => {
            if (salon.latitude && salon.longitude) {
                const placemark = new ymaps.Placemark([salon.latitude, salon.longitude], {
                    balloonContentHeader: salon.name,
                    balloonContentBody: salon.address + '<br>Tel: ' + salon.phone,
                    balloonContentFooter: salon.description
                }, {
                    preset: 'islands#brownDotIcon'
                });
                
                placemark.events.add('click', function () {
                    highlightSalon(index);
                });

                myMap.geoObjects.add(placemark);
            }
        });
    }

    function selectSalon(index, lat, lng) {
        highlightSalon(index);
        if (myMap) {
            myMap.setCenter([lat, lng], 16, {
                duration: 500
            });
        }
    }

    function highlightSalon(index) {
        // Remove active class from all
        document.querySelectorAll('.salon-item').forEach(item => item.classList.remove('active'));
        // Add active class to selected
        const selected = document.querySelector(`.salon-item[data-index="${index}"]`);
        if (selected) selected.classList.add('active');
    }
</script>
@endsection
