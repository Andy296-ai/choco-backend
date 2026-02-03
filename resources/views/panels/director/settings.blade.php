<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Управление Салонами — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resources/css/modals.css') }}">
    <!-- For simplicity in this demo, I will include the CSS directly since asset() might not be configured for these custom paths yet -->
    <style>
        /* CSS from modals.css */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.4); backdrop-filter: blur(8px); display: flex; justify-content: center; align-items: center; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-container { background: #FFFFFF; width: 90%; max-width: 500px; border-radius: 15px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: translateY(20px); transition: all 0.3s ease; border: 1px solid rgba(212, 175, 55, 0.2); }
        .modal-overlay.active .modal-container { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { font-family: 'Playfair Display', serif; color: #3E2723; margin: 0; }
        .close-modal { background: none; border: none; font-size: 24px; color: #888; cursor: pointer; }
        .modal-form .form-group { margin-bottom: 15px; }
        .modal-form label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 5px; color: #3E2723; }
        .modal-form input, .modal-form select, .modal-form textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
        .btn-cancel { background: #f5f5f5; color: #3E2723; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-confirm { background: #D4AF37; color: #3E2723; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }
    </style>
    <style>
        :root {
            --chocolate: #3E2723;
            --gold: #D4AF37;
            --cream: #FFF8E1;
            --white: #FFFFFF;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--chocolate);
            color: var(--white);
            position: fixed;
            padding: 30px 20px;
        }

        .sidebar h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            margin-bottom: 40px;
            text-align: center;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 15px;
        }

        .nav-item a {
            color: #ccc;
            text-decoration: none;
            font-size: 14px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-item a:hover, .nav-item a.active {
            background-color: rgba(255,255,255,0.1);
            color: var(--gold);
        }

        .main-content {
            margin-left: 298px;
            flex: 1;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .content-card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .logout-btn {
            background: none;
            border: 1px solid var(--chocolate);
            color: var(--chocolate);
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            text-transform: uppercase;
        }

        .btn-save {
            background: var(--chocolate);
            color: var(--white);
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.reports') }}">Отчеты</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}" class="active">Настройки</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Управление Салонами</h1>
            <button class="btn-save" style="background-color: var(--gold); color: var(--chocolate);" data-modal="modal-salon-add">Добавить салон</button>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 1fr;">
            @foreach($salons as $salon)
            <div class="content-card" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <h2 style="margin-top: 0; color: var(--chocolate);">{{ $salon->name }}</h2>
                    <p><strong>Адрес:</strong> {{ $salon->address }}</p>
                    <p><strong>Телефон:</strong> {{ $salon->phone }}</p>
                    <p><strong>Описание:</strong> {{ $salon->description }}</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="logout-btn" 
                            style="border-color: var(--gold); color: #888;" 
                            data-modal="modal-salon-edit" 
                            data-edit="{{ json_encode($salon) }}"
                            data-action="{{ route('director.salons.update', $salon->id) }}">
                        Редактировать
                    </button>
                    <form action="{{ route('director.salons.delete', $salon->id) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="logout-btn" style="border-color: #f44336; color: #f44336;" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modals -->
    <div class="modal-overlay" id="modal-salon-add">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Добавить салон</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form action="{{ route('director.salons.store') }}" method="POST" class="ajax-form modal-form">
                @csrf
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <input type="text" name="address" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Создать</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-salon-edit">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Редактировать салон</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-form modal-form">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <input type="text" name="address" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Inline panel-crud.js logic for stability
        document.addEventListener('DOMContentLoaded', function() {
            const modalButtons = document.querySelectorAll('[data-modal]');
            modalButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById(btn.getAttribute('data-modal'));
                    if (modal) {
                        modal.classList.add('active');
                        if (btn.hasAttribute('data-edit')) {
                            const data = JSON.parse(btn.getAttribute('data-edit'));
                            const form = modal.querySelector('form');
                            form.action = btn.getAttribute('data-action');
                            Object.keys(data).forEach(key => {
                                const input = form.querySelector(`[name="${key}"]`);
                                if (input) input.value = data[key];
                            });
                        }
                    }
                });
            });

            document.querySelectorAll('.close-modal, .btn-cancel').forEach(btn => {
                btn.addEventListener('click', () => btn.closest('.modal-overlay').classList.remove('active'));
            });

            document.querySelectorAll('.ajax-form').forEach(form => {
                form.addEventListener('submit', async (e) => {
                    if (form.method.toUpperCase() === 'POST' || form.querySelector('input[name="_method"]')) {
                        e.preventDefault();
                        const btn = form.querySelector('button[type="submit"]');
                        btn.disabled = true;
                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                body: new FormData(form),
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            });
                            if (response.ok) location.reload();
                            else alert('Ошибка при сохранении');
                        } catch (e) { alert('Ошибка сети'); }
                        btn.disabled = false;
                    }
                });
            });
        });
    </script>
</body>
</html>
