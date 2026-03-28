<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Салон красоты Шоколад. Личный кабинет клиента.">
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
            background: var(--chocolate);
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: var(--gold);
            font-size: 28px;
            font-weight: 600;
            text-decoration: none;
            font-family: 'Playfair Display', serif;
            transition: color 0.3s;
        }

        .logo:hover {
            color: var(--gold-light);
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
            align-items: center;
        }

        nav a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 16px;
        }

        nav a:hover {
            color: var(--gold);
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 24px;
            cursor: pointer;
        }

        .client-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--white);
        }

        .logout-btn {
            background: var(--gold);
            color: var(--chocolate);
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }

        /* Page Header */
        .page-header {
            padding: 150px 0 60px;
            background-color: var(--chocolate);
            color: var(--white);
            text-align: center;
        }

        .page-header h1 { 
            font-size: 48px; 
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .page-header p {
            font-size: 18px;
            opacity: 0.9;
        }

        /* Content Card */
        .content-card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin: -50px auto 40px;
            position: relative;
            z-index: 1;
        }

        .content-card h3 {
            color: var(--chocolate);
            margin-bottom: 30px;
            font-size: 28px;
            border-bottom: 3px solid var(--gold);
            padding-bottom: 10px;
        }

        /* Booking Items */
        .booking-item {
            background: var(--cream);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid var(--gold);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .booking-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .booking-item h4 {
            color: var(--chocolate);
            margin-bottom: 10px;
            font-size: 20px;
        }

        .booking-item p {
            color: var(--text-light);
            margin: 5px 0;
            font-size: 14px;
        }

        .booking-item strong {
            color: var(--text-dark);
        }

        /* Status Badges */
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--gold);
            color: var(--chocolate);
        }

        .btn-primary:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }

        /* Success/Error Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            nav ul {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--chocolate);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            }

            nav ul.active {
                display: flex;
            }

            .header-content {
                flex-wrap: wrap;
                gap: 10px;
            }

            .client-info {
                width: 100%;
                justify-content: space-between;
                margin-top: 10px;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .content-card {
                padding: 20px;
                margin: -30px auto 20px;
            }

            .booking-item {
                padding: 15px;
            }

            .booking-item h4 {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .client-info {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            nav ul {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="{{ route('home') }}" class="logo">Шоколад</a>
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
                <nav>
                    <ul id="nav-menu">
                        <li><a href="{{ route('client.dashboard') }}">Личный кабинет</a></li>
                        <li><a href="{{ route('client.bookings') }}">Мои записи</a></li>
                        <li><a href="{{ route('booking') }}">Записаться</a></li>
                        <li><a href="{{ route('home') }}">Главная</a></li>
                        <li><a href="{{ route('services') }}">Услуги</a></li>
                        <li><a href="{{ route('about') }}">О нас</a></li>
                        <li><a href="{{ route('contacts') }}">Контакты</a></li>
                    </ul>
                </nav>
                <div class="client-info">
                    <span>Привет, {{ Auth::guard("client")->user()->name }}!</span>
                    <a href="{{ route('client.logout') }}" class="logout-btn">Выход</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>@yield('page-title', 'Личный кабинет')</h1>
            <p>@yield('page-subtitle')</p>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('nav-menu');
            menu.classList.toggle('active');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('nav-menu');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
