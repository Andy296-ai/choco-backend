<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Портфолио — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Shared Modal CSS */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.4); backdrop-filter: blur(8px); display: flex; justify-content: center; align-items: center; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-container { background: #FFFFFF; width: 90%; max-width: 500px; border-radius: 15px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: translateY(20px); transition: all 0.3s ease; border: 1px solid rgba(212, 175, 55, 0.2); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { font-family: 'Playfair Display', serif; color: #3E2723; margin: 0; }
        .close-modal { background: none; border: none; font-size: 24px; color: #888; cursor: pointer; }
        .modal-form .form-group { margin-bottom: 15px; }
        .modal-form label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 5px; color: #3E2723; }
        .modal-form input, .modal-form select, .modal-form textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 14px; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
        .btn-cancel { background: #f5f5f5; color: #3E2723; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-confirm { background: #D4AF37; color: #3E2723; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }

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

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .portfolio-item {
            aspect-ratio: 1/1;
            background-color: #eee;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .portfolio-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(244, 67, 54, 0.8);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            justify-content: center;
            align-items: center;
            font-size: 18px;
        }

        .portfolio-item:hover .delete-btn {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}" class="active">Портфолио</a></li>
            <li class="nav-item"><a href="{{ route('specialist.materials') }}">Материалы</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Портфолио</h1>
            <button class="btn-add" style="background: var(--gold); border: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; cursor: pointer;" data-modal="modal-portfolio-add">+ Добавить работу</button>
        </div>

        <div class="content-card">
            <div class="portfolio-grid">
                @forelse($items as $item)
                <div class="portfolio-item">
                    <img src="{{ $item->image_path }}" alt="{{ $item->title }}">
                    <form action="{{ route('specialist.portfolio.delete', $item->id) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('Удалить эту работу?')">×</button>
                    </form>
                </div>
                @empty
                <p style="color: #888; text-align: center; width: 100%; grid-column: 1 / -1;">Ваше портфолио пока пусто</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal-overlay" id="modal-portfolio-add">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Добавить работу</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form action="{{ route('specialist.portfolio.store') }}" method="POST" class="ajax-form modal-form">
                @csrf
                <div class="form-group">
                    <label>Ссылка на фото</label>
                    <input type="url" name="image_path" placeholder="https://..." required>
                </div>
                <div class="form-group">
                    <label>Название (необязательно)</label>
                    <input type="text" name="title" placeholder="Например: Цветное окрашивание">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Добавить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById(btn.getAttribute('data-modal'));
                    if (modal) modal.classList.add('active');
                });
            });

            document.querySelectorAll('.close-modal, .btn-cancel').forEach(btn => {
                btn.addEventListener('click', () => btn.closest('.modal-overlay').classList.remove('active'));
            });

            document.querySelectorAll('.ajax-form').forEach(form => {
                form.addEventListener('submit', async (e) => {
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
                });
            });
        });
    </script>
</body>
</html>
