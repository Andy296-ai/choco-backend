<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Панель Директора — Шоколад')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Raleway:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --chocolate: #3E2723;
            --gold: #D4AF37;
            --cream: #FFF8E1;
            --white: #FFFFFF;
            --sidebar-width: 250px;
        }

        * { box-sizing: border-box; }

        button, input, select, textarea {
            font-family: 'Montserrat', sans-serif;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
        }

        /* ── Кнопка мобильного меню ── */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--chocolate);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
        }

        /* ── Оверлей при открытом меню ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 900;
        }
        .sidebar-overlay.active { display: block; }

        /* ── Сайдбар ── */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--chocolate);
            color: var(--white);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .sidebar-top {
            padding: 30px 20px 20px;
            flex-shrink: 0;
        }

        .sidebar h2 {
            font-family: 'Raleway', sans-serif;
            color: var(--gold);
            margin: 0 0 30px;
            text-align: center;
            font-size: 20px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item { margin-bottom: 5px; }

        .nav-item a {
            color: #ccc;
            text-decoration: none;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            display: block;
            padding: 10px 12px;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
        }

        .nav-item a:hover,
        .nav-item a.active {
            background: rgba(255,255,255,0.1);
            color: var(--gold);
        }

        /* ── Пользователь + кнопка выхода внизу ── */
        .sidebar-bottom {
            margin-top: auto;
            padding: 16px 20px 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(212,175,55,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: var(--gold);
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-user-info { flex: 1; min-width: 0; }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            display: block;
            margin-top: 2px;
        }

        .sidebar-logout-btn {
            width: 100%;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            color: rgba(255,255,255,0.75);
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: background 0.2s, color 0.2s;
            text-align: center;
        }

        .sidebar-logout-btn:hover {
            background: rgba(244,67,54,0.25);
            border-color: rgba(244,67,54,0.4);
            color: #ff8a80;
        }

        /* ── Основной контент ── */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 40px;
            min-width: 0;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .content-card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        /* Кнопки в панели — единый стиль */
        .btn-add, .btn-action-gold {
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-add:hover, .btn-action-gold:hover {
            background: #c9a02e;
            transform: translateY(-1px);
        }

        /* ── Toast-уведомления ── */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 500;
            max-width: 360px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            pointer-events: all;
            animation: toastIn 0.3s ease forwards;
            border-left: 4px solid transparent;
        }

        .toast.toast--success {
            background: #f0fdf4;
            color: #166534;
            border-left-color: #22c55e;
        }

        .toast.toast--error {
            background: #fef2f2;
            color: #991b1b;
            border-left-color: #ef4444;
        }

        .toast.toast--info {
            background: #eff6ff;
            color: #1e40af;
            border-left-color: #3b82f6;
        }

        .toast.toast--out {
            animation: toastOut 0.3s ease forwards;
        }

        .toast-icon { font-size: 18px; flex-shrink: 0; }
        .toast-msg { flex: 1; }
        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: 0.5;
            font-size: 16px;
            padding: 0;
            line-height: 1;
            pointer-events: all;
        }
        .toast-close:hover { opacity: 1; }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(40px); }
        }

        /* Shared Modal CSS */
        {!! file_get_contents(resource_path('css/modals.css')) !!}

        /* ── Мобильные ── */
        @media (max-width: 900px) {
            .main-content { padding: 28px; }
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle { display: block; }

            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 70px 15px 20px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .main-content { padding: 65px 12px 16px; }
        }
    </style>

    @yield('styles')
</head>
<body>

<button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Меню">☰</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}" class="{{ Route::is('director.dashboard') ? 'active' : '' }}">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}" class="{{ Route::is('director.employees') ? 'active' : '' }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}" class="{{ Route::is('director.finance') ? 'active' : '' }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}" class="{{ Route::is('director.settings') ? 'active' : '' }}">Настройки</a></li>
            <li class="nav-item"><a href="{{ route('director.clients') }}" class="{{ Route::is('director.clients') ? 'active' : '' }}">Клиенты</a></li>
            <li class="nav-item"><a href="{{ route('director.services') }}" class="{{ Route::is('director.services') ? 'active' : '' }}">Услуги</a></li>
            <li class="nav-item"><a href="{{ route('director.db.index') }}" class="{{ Route::is('director.db*') ? 'active' : '' }}">База данных</a></li>
        </ul>
    </div>

    <div class="sidebar-bottom">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'Д', 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ auth()->user()->name ?? '—' }}</span>
                <span class="sidebar-user-role">Директор</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout-btn">Выйти</button>
        </form>
    </div>
</div>

<div class="main-content" id="main-content">
    @yield('content')
</div>

@yield('modals')

<!-- Toast-контейнер -->
<div id="toast-container"></div>

<script>
    /* ── Мобильное меню ── */
    const mobileToggle   = document.getElementById('mobile-menu-toggle');
    const sidebar        = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
        sidebar.classList.add('open');
        sidebarOverlay.classList.add('active');
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
    }

    mobileToggle.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    sidebarOverlay.addEventListener('click', closeSidebar);

    /* ── Toast-система ── */
    function showToast(message, type = 'success', duration = 4000) {
        const icons = { success: '✓', error: '✕', info: 'ℹ' };
        const container = document.getElementById('toast-container');

        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.innerHTML = `
            <span class="toast-icon">${icons[type] ?? '•'}</span>
            <span class="toast-msg">${message}</span>
            <button class="toast-close" onclick="removeToast(this.parentElement)">×</button>
        `;
        container.appendChild(toast);

        setTimeout(() => removeToast(toast), duration);
    }

    function removeToast(el) {
        if (!el || el.classList.contains('toast--out')) return;
        el.classList.add('toast--out');
        setTimeout(() => el.remove(), 310);
    }

    /* ── Показать flash-сообщения из сессии ── */
    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif
    @if(session('message'))
        showToast(@json(session('message')), 'info');
    @endif

    /* ── Модальные окна ── */
    document.querySelectorAll('.close-modal, .btn-cancel').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-overlay');
            if (modal) modal.classList.remove('active');
        });
    });

    document.querySelectorAll('.ajax-form').forEach(form => {
        if (form.dataset.customSubmit) return;
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (response.ok) {
                    const result = await response.json().catch(() => ({}));
                    showToast(result.message || 'Сохранено успешно', 'success');
                    setTimeout(() => location.reload(), 900);
                } else {
                    const result = await response.json().catch(() => ({}));
                    showToast(result.message || 'Ошибка при сохранении', 'error');
                }
            } catch (err) {
                showToast('Ошибка сети', 'error');
            }
            if (btn) btn.disabled = false;
        });
    });

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('active');
    }
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.remove('active');
    }
</script>

<style>
    {!! file_get_contents(resource_path('css/pagination.css')) !!}
</style>

@yield('scripts')
</body>
</html>
