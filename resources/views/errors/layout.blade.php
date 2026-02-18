<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --chocolate: #3E2723;
            --gold: #D4AF37;
            --cream: #FFF8E1;
            --white: #FFFFFF;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--cream);
            color: var(--chocolate);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        .error-container {
            max-width: 600px;
            background: var(--white);
            padding: 60px 40px;
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(62, 39, 35, 0.1);
            position: relative;
            overflow: hidden;
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--chocolate), var(--gold));
        }

        .error-code {
            font-family: 'Playfair Display', serif;
            font-size: 120px;
            font-weight: 700;
            color: var(--gold);
            margin: 0;
            line-height: 1;
            opacity: 0.8;
        }

        .error-message {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            margin: 20px 0;
            font-weight: 700;
        }

        .error-description {
            font-size: 16px;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-block;
            padding: 15px 35px;
            background-color: var(--chocolate);
            color: var(--white);
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background-color: var(--gold);
            color: var(--chocolate);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .decoration {
            position: absolute;
            font-family: 'Playfair Display', serif;
            font-size: 200px;
            color: var(--cream);
            z-index: -1;
            bottom: -50px;
            right: -20px;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">@yield('code')</div>
        <div class="error-message">@yield('message')</div>
        <div class="error-description">@yield('description')</div>
        <a href="{{ url('/') }}" class="btn-home">Вернуться на главную</a>
        <div class="decoration">@yield('code')</div>
    </div>
</body>
</html>
