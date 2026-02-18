<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Салон красоты Шоколад в Сергиевом Посаде. Профессиональные услуги стилистов, мастеров маникюра и косметологов. Запишитесь онлайн!">
    <title>@yield('title', 'Шоколад — Салон красоты')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <style>
        :root {
            --chocolate: #3E2723;
            --chocolate-light: #5D4037;
            --gold: #D4AF37;
            --gold-light: #F1D36E;
            --cream: #FFF8E1;
            --white: #FFFFFF;
            --text-dark: #212121;
            --text-light: #757575;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background-color: var(--cream); color: var(--text-dark); line-height: 1.6; overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(62, 39, 35, 0.95);
            padding: 15px 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        nav { display: flex; justify-content: space-between; align-items: center; }
        .logo { display: flex; align-items: center; text-decoration: none; }
        .logo img { height: 40px; width: auto; }
        .nav-links { display: flex; list-style: none; gap: 20px; align-items: center; }
        .nav-links a { color: var(--white); text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; transition: color 0.3s ease; }
        .nav-links a:hover, .nav-links a.active { color: var(--gold); }

        .btn-booking {
            background-color: var(--gold);
            color: var(--chocolate) !important;
            padding: 10px 20px;
            border-radius: 2px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px !important;
            transition: all 0.3s ease;
        }
        .btn-booking:hover { background-color: var(--gold-light); transform: translateY(-2px); }

        /* Mobile Elements */
        .mobile-toggle, .mobile-profile { display: none; color: var(--white); font-size: 24px; cursor: pointer; background: none; border: none; }
        
        .mobile-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 80%;
            height: 100vh;
            background: var(--chocolate);
            z-index: 2000;
            padding: 80px 40px;
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            gap: 25px;
            box-shadow: 5px 0 15px rgba(0,0,0,0.3);
        }

        .mobile-menu.active { left: 0; }
        .mobile-menu a { color: var(--white); text-decoration: none; font-size: 18px; text-transform: uppercase; letter-spacing: 1px; }
        .mobile-menu .close-menu { position: absolute; top: 20px; right: 20px; font-size: 30px; color: var(--gold); cursor: pointer; }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1999;
            display: none;
            backdrop-filter: blur(3px);
        }
        .overlay.active { display: block; }

        /* Footer */
        footer { background-color: var(--chocolate); color: var(--white); padding: 60px 0 30px; margin-top: 50px; }
        .footer-content { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px; }
        .footer-section h4 { font-family: 'Playfair Display', serif; font-size: 20px; color: var(--gold); margin-bottom: 20px; }
        .footer-section p, .footer-section li { font-size: 14px; margin-bottom: 10px; color: #ccc; }
        .footer-section ul { list-style: none; }
        .footer-section a { color: #ccc; text-decoration: none; transition: color 0.3s ease; }
        .footer-section a:hover { color: var(--gold); }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 12px; color: #888; }
        .linka {text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 12px; color: #888;}
        .linka:hover {text-align: center; padding-top: 30px; border-top: 1px solid rgba(200, 0, 0, 0.48); font-size: 12px; color: #c8c801ff;}

        @media (max-width: 850px) {
            .nav-links { display: none; }
            .mobile-toggle, .mobile-profile { display: block; }
            nav { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; width: 100%; }
            .logo { grid-column: 2; justify-self: center; }
            .mobile-toggle { grid-column: 1; justify-self: start; }
            .mobile-profile { grid-column: 3; justify-self: end; text-decoration: none; }
            .logo img { height: 35px; }
        }

        /* Toast Notifications */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: var(--chocolate);
            color: var(--white);
            padding: 15px 25px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-left: 5px solid var(--gold);
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 300px;
        }

        .toast.active { transform: translateX(0); }
        .toast.success { border-left-color: #4CAF50; }
        .toast.error { border-left-color: #F44336; }
    </style>
    @yield('styles')
    
    <!-- Стили для улучшений -->
    <style>
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Уведомления */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            max-width: 400px;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
        }
        
        .notification-icon {
            font-size: 20px;
        }
        
        .notification-message {
            flex: 1;
            font-size: 14px;
            color: #333;
        }
        
        .notification-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #999;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            line-height: 1;
        }
        
        .notification-success .notification-content {
            border-left: 4px solid #4caf50;
        }
        
        .notification-error .notification-content {
            border-left: 4px solid #f44336;
        }
        
        .notification-warning .notification-content {
            border-left: 4px solid #ff9800;
        }
        
        /* Кнопка "Наверх" */
        .scroll-top-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .scroll-top-btn.show {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-top-btn:hover {
            background: var(--gold-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        /* Tooltips */
        .tooltip {
            position: absolute;
            background: rgba(62, 39, 35, 0.95);
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10000;
            pointer-events: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(62, 39, 35, 0.95);
        }
        
        /* Breadcrumbs */
        .breadcrumbs {
            padding: 15px 0;
            font-size: 14px;
            color: var(--text-light);
        }
        
        .breadcrumbs a {
            color: var(--gold);
            text-decoration: none;
        }
        
        .breadcrumbs a:hover {
            text-decoration: underline;
        }
        
        .breadcrumbs span {
            margin: 0 8px;
            color: #999;
        }
        
        @media (max-width: 768px) {
            .notification {
                right: 10px;
                left: 10px;
                max-width: none;
            }
            
            .scroll-top-btn {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <button class="mobile-toggle" onclick="toggleMenu()">☰</button>
                
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('logo.svg') }}" alt="Шоколад">
                </a>

                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ Route::is('home') ? 'active' : '' }}">Главная</a></li>
                    <li><a href="{{ route('services') }}" class="{{ Route::is('services') ? 'active' : '' }}">Услуги</a></li>
                    <li><a href="{{ route('about') }}" class="{{ Route::is('about') ? 'active' : '' }}">О нас</a></li>
                    <li><a href="{{ route('gallery') }}" class="{{ Route::is('gallery') ? 'active' : '' }}">Галерея</a></li>
                    <li><a href="{{ route('contacts') }}" class="{{ Route::is('contacts') ? 'active' : '' }}">Контакты</a></li>
                    <li><a href="{{ route('booking') }}" class="btn-booking">Онлайн запись</a></li>
                    <li><a href="{{ route('profile') }}" class="{{ Route::is('profile') ? 'active' : '' }}">Кабинет</a></li>
                </ul>

                <a href="{{ route('profile') }}" class="mobile-profile">👤</a>
            </nav>
        </div>
    </header>

    <div id="toast-container"></div>

    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>
    
    <div class="mobile-menu" id="mobileMenu">
        <span class="close-menu" onclick="toggleMenu()">×</span>
        <a href="{{ route('home') }}" onclick="toggleMenu()">Главная</a>
        <a href="{{ route('services') }}" onclick="toggleMenu()">Услуги</a>
        <a href="{{ route('about') }}" onclick="toggleMenu()">О нас</a>
        <a href="{{ route('gallery') }}" onclick="toggleMenu()">Галерея</a>
        <a href="{{ route('contacts') }}" onclick="toggleMenu()">Контакты</a>
        <a href="{{ route('booking') }}" class="btn-booking" style="text-align: center;" onclick="toggleMenu()">Онлайн запись</a>
    </div>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="{{ asset('logo.svg') }}" alt="Шоколад" style="height: 50px; margin-bottom: 20px;">
                    <p>Ваш идеальный образ — наша главная цель. Работаем для вас с любовью и профессионализмом.</p>
                </div>
                <div class="footer-section">
                    <h4>Навигация</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Главная</a></li>
                        <li><a href="{{ route('services') }}">Услуги</a></li>
                        <li><a href="{{ route('about') }}">О нас</a></li>
                        <li><a href="{{ route('contacts') }}">Контакты</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Контакты</h4>
                    <p>г. Сергиев Посад, ул. Вознесенская, 46</p>
                    <p>Тел: +7 (926) 607-07-07</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Салон красоты «Шоколад». Cайт дубл. и ещё в тадии разработки <a class="linka" href="http://www.shodruz.fun">Шодрузом</a></p>
            </div>
        </div>
    </footer>
    
    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('overlay');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            
            if (menu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `<span>${message}</span>`;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => toast.classList.add('active'), 10);
            
            // Auto remove
            setTimeout(() => {
                toast.classList.remove('active');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Global check for session messages
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
    @vite(['resources/js/improvements.js'])
    @yield('scripts')
</body>
</html>
