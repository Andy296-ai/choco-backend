<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Услуги — Шоколад</title>
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
        .btn-edit { color: var(--gold); background: none; border: none; cursor: pointer; font-weight: 600; }
        .btn-delete { color: #f44336; background: none; border: none; cursor: pointer; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}">Записи</a></li>
            <li class="nav-item"><a href="{{ route('admin.clients') }}">Клиенты</a></li>
            <li class="nav-item"><a href="{{ route('admin.masters') }}">Мастера</a></li>
            <li class="nav-item"><a href="{{ route('admin.services') }}" class="active">Услуги</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Услуги</h1>
            <button class="btn-add" style="background: var(--gold); border: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; cursor: pointer;" onclick="openModal('modal-service-add')">+ Новая услуга</button>
        </div>

        <div class="content-card">
            <ul class="service-list">
                @forelse($services as $service)
                <li class="service-item">
                    <div class="service-info">
                        <h4>{{ $service->name }}</h4>
                        <p>{{ $service->description }} ({{ $service->duration_minutes }} мин)</p>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <div class="service-price" style="margin-right: 20px;">{{ number_format($service->price, 0, '.', ' ') }} ₽</div>
                        <button class="btn-edit" onclick="editService({{ json_encode($service) }})">Редакт.</button>
                        <form action="{{ route('admin.services.delete', $service->id) }}" method="POST" class="ajax-form" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Удалить услугу?')">×</button>
                        </form>
                    </div>
                </li>
                @empty
                <li class="service-item">Услуг пока нет</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal-overlay" id="modal-service-add">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Добавить услугу</h3>
                <button class="close-modal" onclick="closeModal('modal-service-add')">&times;</button>
            </div>
            <form action="{{ route('admin.services.store') }}" method="POST" class="ajax-form modal-form">
                @csrf
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Цена (₽)</label>
                    <input type="number" name="price" required>
                </div>
                <div class="form-group">
                    <label>Длительность (мин)</label>
                    <input type="number" name="duration_minutes" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-service-add')">Отмена</button>
                    <button type="submit" class="btn-confirm">Создать</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-service-edit">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Редактировать услугу</h3>
                <button class="close-modal" onclick="closeModal('modal-service-edit')">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-form modal-form" id="form-service-edit">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Цена (₽)</label>
                    <input type="number" name="price" required>
                </div>
                <div class="form-group">
                    <label>Длительность (мин)</label>
                    <input type="number" name="duration_minutes" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-service-edit')">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function editService(service) {
            const form = document.getElementById('form-service-edit');
            form.action = `{{ url('/admin/services') }}/${service.id}`;
            form.querySelector('[name="name"]').value = service.name;
            form.querySelector('[name="description"]').value = service.description || '';
            form.querySelector('[name="price"]').value = service.price;
            form.querySelector('[name="duration_minutes"]').value = service.duration_minutes;
            openModal('modal-service-edit');
        }

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
                    const result = await response.json();
                    if (response.ok) location.reload();
                    else alert(result.message || 'Ошибка при сохранении');
                } catch (e) { alert('Ошибка сети'); }
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>
