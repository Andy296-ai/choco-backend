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
        body { 
            font-family: 'Montserrat', sans-serif; 
            background-color: var(--cream); 
            color: var(--text-dark); 
            line-height: 1.6; 
            overflow-x: hidden; 
        }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* Client Navigation */
        .client-nav {
            background: var(--chocolate);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .client-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .client-nav .logo {
            color: var(--gold);
            font-size: 24px;
            font-weight: 600;
            text-decoration: none;
            font-family: 'Playfair Display', serif;
        }

        .client-nav .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .client-nav .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .client-nav .nav-links a:hover {
            color: var(--gold);
        }

        .client-nav .user-info {
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .client-nav .logout-btn {
            background: var(--gold);
            color: var(--chocolate);
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .client-nav .logout-btn:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--chocolate), var(--chocolate-light));
            color: var(--white);
            padding: 80px 0 40px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .client-nav .container {
                flex-direction: column;
                gap: 15px;
            }

            .client-nav .nav-links {
                flex-wrap: wrap;
                justify-content: center;
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
        }
    </style>
</head>
<body>
    <!-- Client Navigation -->
    <nav class="client-nav">
        <div class="container">
            <a href="{{ route('home') }}" class="logo">Шоколад</a>
            <div class="nav-links">
                <a href="{{ route('client.dashboard') }}">Личный кабинет</a>
                <a href="{{ route('client.bookings') }}">Мои записи</a>
                <a href="{{ route('booking') }}">Записаться</a>
                <a href="{{ route('home') }}">Главная</a>
            </div>
            <div class="user-info">
                <span>Привет, {{ Auth::guard("client")->user()->name }}!</span>
                <a href="{{ route('client.logout') }}" class="logout-btn">Выход</a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>@yield('page-title', 'Личный кабинет')</h1>
            <p>@yield('page-subtitle')</p>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
