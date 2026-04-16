@extends(Auth::user()->role === 'director' ? 'layouts.director' : 'layouts.admin')

@php
    $clientsIndexRoute = Auth::user()->role === 'director' ? 'director.clients' : 'admin.clients';
@endphp

@section('title', 'Клиенты — Шоколад')

@section('content')
    <div class="header">
        <h1>Клиенты</h1>
        <button class="btn-add" style="background: var(--gold); border: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; cursor: pointer;" onclick="openModal('modal-client-add')">+ Новый клиент</button>
    </div>

    <!-- Форма поиска и фильтров -->
    <div class="content-card" style="margin-bottom: 20px; padding: 20px;">
        <form method="GET" action="{{ route($clientsIndexRoute) }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" placeholder="Поиск по имени, телефону или email..." 
                       value="{{ request('search') }}" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            
            <div style="min-width: 150px;">
                <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                    <option value="">Все статусы</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивные</option>
                </select>
            </div>
            
            <button type="submit" style="background: var(--chocolate); color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; cursor: pointer;">
                Найти
            </button>
            
            <a href="{{ route($clientsIndexRoute) }}" style="background: #f5f5f5; color: #666; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; display: inline-block;">
                Сбросить
            </a>
        </form>
    </div>

    <div class="content-card">
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 15px; border-bottom: 2px solid #f5f5f5; color: #888; font-size: 14px;">Имя</th>
                    <th style="text-align: left; padding: 15px; border-bottom: 2px solid #f5f5f5; color: #888; font-size: 14px;">Телефон</th>
                    <th style="text-align: left; padding: 15px; border-bottom: 2px solid #f5f5f5; color: #888; font-size: 14px;">Telegram</th>
                    <th style="text-align: left; padding: 15px; border-bottom: 2px solid #f5f5f5; color: #888; font-size: 14px;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td style="padding: 15px; border-bottom: 1px solid #f5f5f5; font-size: 14px;">{{ $client->name }}</td>
                    <td style="padding: 15px; border-bottom: 1px solid #f5f5f5; font-size: 14px;">{{ $client->phone ?? 'Не указан' }}</td>
                    <td style="padding: 15px; border-bottom: 1px solid #f5f5f5; font-size: 14px;">{{ $client->telegram_username ? '@'.$client->telegram_username : '-' }}</td>
                    <td style="padding: 15px; border-bottom: 1px solid #f5f5f5; font-size: 14px;"><a href="#" style="color: var(--gold);" onclick="viewClientProfile({{ $client->id }}); return false;">Профиль</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 15px; border-bottom: 1px solid #f5f5f5; font-size: 14px;">Клиенты не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px; text-align: center;">
            {{ $clients->links() }}
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modals -->
    <div class="modal-overlay" id="modal-client-add">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Новый клиент</h3>
                <button class="close-modal" onclick="closeModal('modal-client-add')">&times;</button>
            </div>
            <form action="{{ Auth::user()->role === 'director' ? route('director.clients.store') : route('admin.clients.store') }}" method="POST" class="ajax-form modal-form" onsubmit="handleFormSubmit(event)">
                @csrf
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Telegram (@username)</label>
                    <input type="text" name="telegram_username">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-client-add')">Отмена</button>
                    <button type="submit" class="btn-confirm">Создать</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-client-profile">
        <div class="modal-container" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Профиль клиента</h3>
                <button class="close-modal" onclick="closeModal('modal-client-profile')">&times;</button>
            </div>
            <div style="padding: 20px;" id="client-profile-content">
                <p>Загрузка...</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function viewClientProfile(clientId) {
            openModal('modal-client-profile');
            document.getElementById('client-profile-content').innerHTML = '<p>Загрузка...</p>';
            
            const baseUrl = '{{ Auth::user()->role === "director" ? url("/director/clients") : url("/admin/clients") }}';
            fetch(`${baseUrl}/${clientId}`)
                .then(res => res.json())
                .then(data => {
                    const client = data.client;
                    const bookings = data.bookings;
                    
                    let html = `
                        <div style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                            <h4 style="margin: 0 0 5px; color: var(--chocolate); font-size: 18px;">${client.name}</h4>
                            <p style="margin: 0; color: #666; font-size: 14px;">📞 ${client.phone || 'Не указан'} | 📧 ${client.email || 'Нет email'}</p>
                            <p style="margin: 5px 0 0; color: #888; font-size: 12px;">💬 ${client.telegram_username ? '@'+client.telegram_username : 'Нет Telegram'}</p>
                        </div>

                        <div style="background: #fff8e1; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffe082;">
                            <h5 style="margin: 0 0 10px; color: var(--chocolate); font-size: 14px;">Индивидуальные настройки</h5>
                            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <div style="flex: 1;">
                                    <label style="display: block; font-size: 11px; color: #888; margin-bottom: 3px;">Скидка (%)</label>
                                    <input type="number" id="client-discount-input" value="${client.discount || 0}" min="0" max="100" 
                                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                </div>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label style="display: block; font-size: 11px; color: #888; margin-bottom: 3px;">Внутренние заметки</label>
                                <textarea id="client-notes-input" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; height: 60px;">${client.notes || ''}</textarea>
                            </div>
                            <button onclick="saveClientSettings(${client.id})" 
                                    style="background: var(--gold); color: white; border: none; padding: 8px 15px; border-radius: 5px; font-weight: 600; cursor: pointer; width: 100%; font-size: 13px;">
                                Сохранить настройки
                            </button>
                        </div>

                        <h5 style="margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; font-size: 14px; color: #444;">История посещений</h5>
                        <ul style="list-style: none; padding: 0; margin: 0; max-height: 250px; overflow-y: auto;">
                    `;
                    
                    if (bookings.length === 0) {
                        html += `<li style="padding: 10px 0; color: #888; text-align: center;">Записей пока нет</li>`;
                    } else {
                        bookings.forEach(b => {
                            const date = new Date(b.start_time).toLocaleString('ru-RU', {day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute:'2-digit'});
                            html += `
                                <li style="padding: 10px; border-bottom: 1px solid #f5f5f5; font-size: 13px;">
                                    <div style="font-weight: 600; color: var(--chocolate);">${b.service ? b.service.name : 'Услуга удалена'}</div>
                                    <div style="color: #666; margin-top: 3px;">📅 ${date} | 👩‍⚕️ Мастер: ${b.specialist ? b.specialist.name : 'Удален'}</div>
                                    <div style="margin-top: 3px;"><span style="background: #eee; padding: 2px 5px; border-radius: 3px; font-size: 11px;">Статус: ${b.status}</span></div>
                                </li>
                            `;
                        });
                    }
                    
                    html += `</ul>`;
                    document.getElementById('client-profile-content').innerHTML = html;
                })
                .catch(err => {
                    document.getElementById('client-profile-content').innerHTML = '<p style="color:red;">Ошибка загрузки данных</p>';
                });
        }

        function saveClientSettings(clientId) {
            const discount = document.getElementById('client-discount-input').value;
            const notes = document.getElementById('client-notes-input').value;
            
            const baseUrl = '{{ Auth::user()->role === "director" ? url("/director/clients") : url("/admin/clients") }}';
            
            fetch(`${baseUrl}/${clientId}/discount`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    discount: parseInt(discount),
                    notes: notes
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.message) {
                    alert('Данные клиента успешно сохранены');
                    location.reload();
                } else {
                    alert(data.error || 'Произошла ошибка при сохранении');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Сетевая ошибка при сохранении');
            });
        }
        
        function handleFormSubmit(e) {
            e.preventDefault();
            const form = e.target;
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.error) alert(data.error);
                else location.reload();
            })
            .catch(err => {
                console.error(err);
                alert('Произошла ошибка');
            });
        }
    </script>
@endsection


