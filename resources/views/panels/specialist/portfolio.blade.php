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
        .modal-container { background: #FFFFFF; width: 90%; max-width: 500px; border-radius: 15px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); transform: translateY(20px); transition: all 0.3s ease; border: 1px solid rgba(212, 175, 55, 0.2); max-height: 90vh; overflow-y: auto; }
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

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
        }

        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            background: var(--chocolate);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--chocolate);
            color: var(--white);
            position: fixed;
            padding: 30px 20px;
            overflow-y: auto;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            margin-bottom: 40px;
            text-align: center;
            font-size: 20px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
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
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: var(--chocolate);
        }

        .content-card {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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

        .btn-add {
            background: var(--gold);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            color: var(--chocolate);
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

        /* Мобильная версия */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 60px 15px 15px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header h1 {
                font-size: 20px;
            }

            .content-card {
                padding: 15px;
            }

            .portfolio-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .btn-add {
                width: 100%;
            }

            .modal-container {
                width: 95%;
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 18px;
            }

            .content-card {
                padding: 10px;
            }

            .portfolio-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>
    
    <div class="sidebar" id="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.schedule') }}">Расписание</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}" class="active">Портфолио</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Портфолио</h1>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%;">
                <button class="btn-add" data-modal="modal-portfolio-add" style="flex: 1; min-width: 150px;">+ Добавить работу</button>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="portfolio-grid">
                @forelse($items as $item)
                <div class="portfolio-item">
                    <img src="{{ $item->image_path }}" alt="{{ $item->title ?? 'Работа' }}">
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
                <div class="form-group">
                    <label>Описание (необязательно)</label>
                    <textarea name="description" rows="3" placeholder="Опишите работу, используемые техники, результат..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Добавить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Мобильное меню
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
            
            // Закрытие меню при клике вне его
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(event.target) && 
                    !mobileMenuToggle.contains(event.target) &&
                    sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
            });
        }

        // Модальные окна
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
