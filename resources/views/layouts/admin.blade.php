<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Панель Администратора — Шоколад')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
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

        .content-card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        /* Shared Modal CSS */
        {!! file_get_contents(resource_path('css/modals.css')) !!}
    </style>
    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">Записи</a></li>
            <li class="nav-item"><a href="{{ route('admin.clients') }}" class="{{ Route::is('admin.clients') ? 'active' : '' }}">Клиенты</a></li>
            <li class="nav-item"><a href="{{ route('admin.masters') }}" class="{{ Route::is('admin.masters') ? 'active' : '' }}">Мастера</a></li>
            <li class="nav-item"><a href="{{ route('admin.services') }}" class="{{ Route::is('admin.services') ? 'active' : '' }}">Услуги</a></li>
        </ul>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    @yield('modals')

    <script>
        document.querySelectorAll('.close-modal, .btn-cancel').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal-overlay');
                if (modal) modal.classList.remove('active');
            });
        });

        document.querySelectorAll('.ajax-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                // Keep the original logic if it exists in the view, but provide a base one if not
                if (form.dataset.customSubmit) return;
                
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
                    if (response.ok) location.reload();
                    else {
                        const result = await response.json();
                        alert(result.message || 'Ошибка при сохранении');
                    }
                } catch (e) { alert('Ошибка сети'); }
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
