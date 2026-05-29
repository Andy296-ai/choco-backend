<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Портфолио — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/modals.css')) !!}
        {!! file_get_contents(resource_path('css/pagination.css')) !!}

        :root {
            --chocolate: #3E2723;
            --gold: #D4AF37;
            --cream: #FFF8E1;
            --white: #FFFFFF;
            --sidebar-width: 250px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
        }

        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px; left: 15px;
            z-index: 1100;
            background: var(--chocolate);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 900;
        }
        .sidebar-overlay.active { display: block; }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--chocolate);
            color: var(--white);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-top { padding: 30px 20px 20px; flex-shrink: 0; }

        .sidebar h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            margin: 0 0 30px;
            text-align: center;
            font-size: 20px;
        }

        .nav-menu { list-style: none; padding: 0; margin: 0; }
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
        .nav-item a:hover, .nav-item a.active {
            background: rgba(255,255,255,0.1);
            color: var(--gold);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 16px 20px 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        .sidebar-user { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }

        .sidebar-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(212,175,55,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: var(--gold);
            flex-shrink: 0; overflow: hidden;
        }
        .sidebar-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .sidebar-user-info { flex: 1; min-width: 0; }

        .sidebar-user-name {
            font-size: 13px; font-weight: 600; color: var(--white);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;
        }

        .sidebar-user-role { font-size: 11px; color: rgba(255,255,255,0.5); display: block; margin-top: 2px; }

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

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .header h1 { margin: 0; font-size: 24px; color: var(--chocolate); }

        .content-card {
            background: var(--white);
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        /* ── Портфолио-сетка ── */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 10px;
        }

        .portfolio-item {
            background: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
            transition: box-shadow 0.25s, transform 0.25s;
        }

        .portfolio-item:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transform: translateY(-3px);
        }

        .portfolio-thumb {
            width: 100%;
            aspect-ratio: 1/1;
            overflow: hidden;
            position: relative;
            cursor: zoom-in;
        }

        .portfolio-thumb img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .portfolio-thumb:hover img { transform: scale(1.06); }

        /* Кнопки поверх фото */
        .portfolio-thumb-actions {
            position: absolute;
            top: 8px; right: 8px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .portfolio-item:hover .portfolio-thumb-actions { opacity: 1; }

        .thumb-btn {
            width: 32px; height: 32px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
            transition: transform 0.15s;
        }
        .thumb-btn:hover { transform: scale(1.1); }

        .thumb-btn--zoom { background: rgba(62,39,35,0.75); color: white; }
        .thumb-btn--edit { background: rgba(212,175,55,0.9); color: var(--chocolate); }
        .thumb-btn--del  { background: rgba(244,67,54,0.8); color: white; }

        /* Текст под фото */
        .portfolio-info { padding: 12px 14px 14px; }

        .portfolio-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--chocolate);
            margin: 0 0 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .portfolio-desc {
            font-size: 12px;
            color: #777;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-add {
            background: var(--gold);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            cursor: pointer;
            color: var(--chocolate);
            transition: background 0.2s, transform 0.15s;
        }
        .btn-add:hover { background: #c9a02e; transform: translateY(-1px); }

        /* ── Toast ── */
        #toast-container {
            position: fixed; top: 20px; right: 20px;
            z-index: 9999;
            display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
        }

        .toast {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px; border-radius: 10px;
            font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 500;
            max-width: 360px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            pointer-events: all;
            animation: toastIn 0.3s ease forwards;
            border-left: 4px solid transparent;
        }
        .toast.toast--success { background:#f0fdf4; color:#166534; border-left-color:#22c55e; }
        .toast.toast--error   { background:#fef2f2; color:#991b1b; border-left-color:#ef4444; }
        .toast.toast--out     { animation: toastOut 0.3s ease forwards; }
        .toast-close { background:none; border:none; cursor:pointer; color:inherit; opacity:.5; font-size:16px; padding:0; }
        .toast-close:hover { opacity:1; }

        @keyframes toastIn  { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }
        @keyframes toastOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(40px)} }

        /* ── Lightbox ── */
        #lightbox {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.88);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
        }
        #lightbox.active { display: flex; }

        #lightbox-img {
            max-width: 90vw;
            max-height: 75vh;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        #lightbox-caption {
            margin-top: 16px;
            text-align: center;
            color: rgba(255,255,255,0.9);
        }

        #lightbox-caption .lb-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        #lightbox-caption .lb-desc {
            font-size: 13px;
            opacity: 0.75;
            max-width: 500px;
            margin: 0 auto;
        }

        #lightbox-close {
            position: absolute; top: 20px; right: 24px;
            background: none; border: none;
            color: white; font-size: 32px; cursor: pointer;
            opacity: 0.7; line-height: 1;
        }
        #lightbox-close:hover { opacity: 1; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }
        .empty-state-icon { font-size: 52px; margin-bottom: 16px; opacity: 0.35; }
        .empty-state h3 { font-family: 'Playfair Display',serif; color: var(--chocolate); margin-bottom: 8px; }

        /* Мобильные */
        @media (max-width: 768px) {
            .mobile-menu-toggle { display: block; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 70px 15px 20px; }
            .portfolio-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 14px; }
            .portfolio-thumb-actions { opacity: 1; }
        }

        @media (max-width: 480px) {
            .portfolio-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        }
    </style>
</head>
<body>
<button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('specialist.dashboard') }}">Мой график</a></li>
            <li class="nav-item"><a href="{{ route('specialist.clients') }}">Мои клиенты</a></li>
            <li class="nav-item"><a href="{{ route('specialist.schedule') }}">Расписание</a></li>
            <li class="nav-item"><a href="{{ route('specialist.portfolio') }}" class="active">Портфолио</a></li>
        </ul>
    </div>
    <div class="sidebar-bottom">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'М', 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ auth()->user()->name ?? '—' }}</span>
                <span class="sidebar-user-role">Мастер</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout-btn">Выйти</button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Портфолио</h1>
        <button class="btn-add" onclick="openModal('modal-portfolio-add')">+ Добавить работу</button>
    </div>

    <div class="content-card">
        @if($items->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">🖼️</div>
                <h3>Портфолио пока пустое</h3>
                <p>Добавьте первую работу, чтобы клиенты видели ваши результаты</p>
            </div>
        @else
            <div class="portfolio-grid">
                @foreach($items as $item)
                <div class="portfolio-item">
                    <div class="portfolio-thumb"
                         onclick="openLightbox('{{ $item->image_path }}', '{{ addslashes($item->title ?? '') }}', '{{ addslashes($item->description ?? '') }}')">
                        <img src="{{ $item->image_path }}" alt="{{ $item->title ?? 'Работа' }}" loading="lazy">
                        <div class="portfolio-thumb-actions">
                            <button type="button" class="thumb-btn thumb-btn--zoom"
                                    onclick="event.stopPropagation(); openLightbox('{{ $item->image_path }}', '{{ addslashes($item->title ?? '') }}', '{{ addslashes($item->description ?? '') }}')">
                                🔍
                            </button>
                            <button type="button" class="thumb-btn thumb-btn--edit"
                                    onclick="event.stopPropagation(); openEditModal({{ $item->id }}, '{{ addslashes($item->image_path) }}', '{{ addslashes($item->title ?? '') }}', '{{ addslashes($item->description ?? '') }}')">
                                ✏️
                            </button>
                            <button type="button" class="thumb-btn thumb-btn--del"
                                    onclick="event.stopPropagation(); deleteItem({{ $item->id }})">
                                ×
                            </button>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        @if($item->title)
                            <div class="portfolio-title">{{ $item->title }}</div>
                        @endif
                        @if($item->description)
                            <div class="portfolio-desc">{{ $item->description }}</div>
                        @endif
                        @if(!$item->title && !$item->description)
                            <div class="portfolio-title" style="color:#bbb;">Без названия</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- ── Lightbox ── -->
<div id="lightbox" onclick="closeLightbox()">
    <button id="lightbox-close" onclick="closeLightbox()">×</button>
    <img id="lightbox-img" src="" alt="">
    <div id="lightbox-caption">
        <div id="lightbox-title" class="lb-title"></div>
        <div id="lightbox-desc" class="lb-desc"></div>
    </div>
</div>

<!-- Toast -->
<div id="toast-container"></div>

<!-- Модал добавления -->
<div class="modal-overlay" id="modal-portfolio-add">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Добавить работу</h3>
            <button class="close-modal">&times;</button>
        </div>
        <form id="form-add" action="{{ route('specialist.portfolio.store') }}" method="POST" class="modal-form" enctype="multipart/form-data">
            @csrf
            {{-- Переключатель источника --}}
            <div style="display:flex; gap:0; margin-bottom:16px; border:1px solid #ddd; border-radius:6px; overflow:hidden;">
                <button type="button" id="tab-file" onclick="switchTab('file')"
                        style="flex:1; padding:9px; border:none; background:var(--chocolate); color:white; font-family:'Montserrat',sans-serif; font-size:13px; font-weight:600; cursor:pointer;">
                    📁 Файл с устройства
                </button>
                <button type="button" id="tab-url" onclick="switchTab('url')"
                        style="flex:1; padding:9px; border:none; background:#f5f5f5; color:#666; font-family:'Montserrat',sans-serif; font-size:13px; font-weight:600; cursor:pointer;">
                    🔗 Ссылка URL
                </button>
            </div>
            <div id="pane-file" class="form-group">
                <label>Фото с устройства (JPG, PNG, WebP · до 5 МБ)</label>
                <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp"
                       style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px; font-size:13px;">
            </div>
            <div id="pane-url" class="form-group" style="display:none;">
                <label>Ссылка на фото</label>
                <input type="url" name="image_path" placeholder="https://...">
            </div>
            <div class="form-group">
                <label>Название (необязательно)</label>
                <input type="text" name="title" placeholder="Например: Цветное окрашивание">
            </div>
            <div class="form-group">
                <label>Описание (необязательно)</label>
                <textarea name="description" rows="3" placeholder="Техники, результат..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel">Отмена</button>
                <button type="submit" class="btn-confirm">Добавить</button>
            </div>
        </form>
    </div>
</div>

<!-- Модал редактирования -->
<div class="modal-overlay" id="modal-portfolio-edit">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Редактировать работу</h3>
            <button class="close-modal">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST" class="modal-form">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label>Ссылка на фото</label>
                <input type="url" name="image_path" required>
            </div>
            <div class="form-group">
                <label>Название</label>
                <input type="text" name="title">
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    /* ── Мобильное меню ── */
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    mobileToggle.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('active'); });
    overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('active'); });

    /* ── Toast ── */
    function showToast(msg, type = 'success') {
        const icons = { success: '✓', error: '✕' };
        const c = document.getElementById('toast-container');
        const t = document.createElement('div');
        t.className = `toast toast--${type}`;
        t.innerHTML = `<span>${icons[type]}</span><span style="flex:1">${msg}</span><button class="toast-close" onclick="removeToast(this.parentElement)">×</button>`;
        c.appendChild(t);
        setTimeout(() => removeToast(t), 4000);
    }
    function removeToast(el) {
        if (!el || el.classList.contains('toast--out')) return;
        el.classList.add('toast--out');
        setTimeout(() => el.remove(), 310);
    }

    @if(session('success')) showToast(@json(session('success')), 'success'); @endif
    @if(session('error'))   showToast(@json(session('error')), 'error');   @endif

    /* ── Модальные окна ── */
    function openModal(id) { document.getElementById(id)?.classList.add('active'); }
    function closeModal(id) { document.getElementById(id)?.classList.remove('active'); }

    document.querySelectorAll('.close-modal, .btn-cancel').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.modal-overlay').classList.remove('active'));
    });

    /* ── Переключение вкладок URL/Файл ── */
    function switchTab(tab) {
        const isFile = tab === 'file';
        document.getElementById('pane-file').style.display = isFile ? '' : 'none';
        document.getElementById('pane-url').style.display  = isFile ? 'none' : '';
        document.getElementById('tab-file').style.cssText = isFile
            ? 'flex:1;padding:9px;border:none;background:var(--chocolate);color:white;font-family:\'Montserrat\',sans-serif;font-size:13px;font-weight:600;cursor:pointer;'
            : 'flex:1;padding:9px;border:none;background:#f5f5f5;color:#666;font-family:\'Montserrat\',sans-serif;font-size:13px;font-weight:600;cursor:pointer;';
        document.getElementById('tab-url').style.cssText = isFile
            ? 'flex:1;padding:9px;border:none;background:#f5f5f5;color:#666;font-family:\'Montserrat\',sans-serif;font-size:13px;font-weight:600;cursor:pointer;'
            : 'flex:1;padding:9px;border:none;background:var(--chocolate);color:white;font-family:\'Montserrat\',sans-serif;font-size:13px;font-weight:600;cursor:pointer;';
        // сбрасываем неактивное поле
        if (isFile) document.querySelector('#pane-url input').value = '';
        else document.querySelector('#pane-file input').value = '';
    }

    /* ── Добавить работу (AJAX, multipart) ── */
    document.getElementById('form-add').addEventListener('submit', async e => {
        e.preventDefault();
        const btn = e.target.querySelector('[type=submit]');
        btn.disabled = true;
        btn.textContent = 'Загрузка...';
        const fd = new FormData(e.target);
        const res = await fetch(e.target.action, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
        }).catch(() => null);
        btn.disabled = false;
        btn.textContent = 'Добавить';
        if (res?.ok) { showToast('Работа добавлена'); setTimeout(() => location.reload(), 900); }
        else {
            const err = await res?.json().catch(() => ({}));
            showToast(err?.message || 'Ошибка при добавлении', 'error');
        }
    });

    /* ── Редактировать работу ── */
    function openEditModal(id, imageUrl, title, description) {
        const form = document.getElementById('form-edit');
        form.action = `{{ url('/specialist/portfolio') }}/${id}`;
        form.querySelector('[name=image_path]').value = imageUrl;
        form.querySelector('[name=title]').value = title;
        form.querySelector('[name=description]').value = description;
        openModal('modal-portfolio-edit');
    }

    document.getElementById('form-edit').addEventListener('submit', async e => {
        e.preventDefault();
        const btn = e.target.querySelector('[type=submit]');
        btn.disabled = true;
        const res = await fetch(e.target.action, {
            method: 'POST',
            body: new FormData(e.target),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
        }).catch(() => null);
        btn.disabled = false;
        if (res?.ok) { showToast('Работа обновлена'); setTimeout(() => location.reload(), 900); }
        else showToast('Ошибка при сохранении', 'error');
    });

    /* ── Удалить работу ── */
    async function deleteItem(id) {
        if (!confirm('Удалить эту работу?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/specialist/portfolio') }}/${id}`;
        form.innerHTML = `<input name="_token" value="${csrfToken}"><input name="_method" value="DELETE">`;
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
        }).catch(() => null);
        if (res?.ok) { showToast('Работа удалена'); setTimeout(() => location.reload(), 700); }
        else showToast('Ошибка при удалении', 'error');
    }

    /* ── Lightbox ── */
    function openLightbox(src, title, desc) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox-title').textContent = title;
        document.getElementById('lightbox-desc').textContent = desc;
        document.getElementById('lightbox-title').style.display = title ? 'block' : 'none';
        document.getElementById('lightbox-desc').style.display = desc ? 'block' : 'none';
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
</body>
</html>
