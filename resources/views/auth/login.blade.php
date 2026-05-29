<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход в систему — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Raleway:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --chocolate: #3E2723;
            --gold: #D4AF37;
            --cream: #FFF8E1;
            --white: #FFFFFF;
            --text-light: #795548;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            background: var(--white);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-card h1 {
            font-family: 'Raleway', sans-serif;
            color: var(--chocolate);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: var(--chocolate);
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: var(--chocolate);
            color: var(--white);
            border: none;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.1s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: var(--gold);
            color: var(--chocolate);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .error {
            color: #d32f2f;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 10px;
            background: #ffebee;
            border-radius: 5px;
        }

        .back-home {
            display: inline-block;
            margin-top: 25px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-home:hover {
            color: var(--chocolate);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>ШОКОЛАД</h1>
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="loginInput">Логин</label>
                <input type="text" id="loginInput" name="login" value="{{ old('login') }}" required autofocus autocomplete="username">
            </div>
            <div class="form-group">
                <label for="passwordInput">Пароль</label>
                <input type="password" id="passwordInput" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn-login">Войти</button>
        </form>
        <a href="{{ route('home') }}" class="back-home">← Вернуться на сайт</a>
    </div>
</body>
</html>
