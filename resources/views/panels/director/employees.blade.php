<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Сотрудники — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Modals CSS */
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #f5f5f5;
            color: #888;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
        }

        .btn-add {
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            padding: 10px 20px;
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
            <li class="nav-item"><a href="{{ route('director.employees') }}" class="active">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}">Настройки</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Сотрудники</h1>
            <button class="btn-add" data-modal="modal-employee-add">+ Добавить сотрудника</button>
        </div>

        <div class="content-card">
            <h2>Администраторы</h2>
            <table>
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Салон</th>
                        <th>Телефон</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->salon ? $admin->salon->name : 'Не назначен' }}</td>
                        <td>{{ $admin->phone ?? '—' }}</td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <a href="#" style="color: var(--gold);" 
                                   data-modal="modal-employee-edit" 
                                   data-edit="{{ json_encode($admin) }}"
                                   data-action="{{ route('director.employees.update', $admin->id) }}">
                                    Редактировать
                                </a>
                                <form action="{{ route('director.employees.delete', $admin->id) }}" method="POST" class="ajax-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color: #f44336; cursor:pointer;" onclick="return confirm('Удалить сотрудника?')">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">Администраторы не найдены</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h2 style="margin-top: 40px;">Мастера</h2>
            <table>
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Салон</th>
                        <th>Телефон</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($masters as $master)
                    <tr>
                        <td>{{ $master->name }}</td>
                        <td>{{ $master->salon ? $master->salon->name : 'Не назначен' }}</td>
                        <td>{{ $master->phone ?? '—' }}</td>
                        <td>
                            <div style="display: flex; gap: 10px;">
                                <a href="#" style="color: var(--gold);" 
                                   data-modal="modal-employee-edit" 
                                   data-edit="{{ json_encode($master) }}"
                                   data-action="{{ route('director.employees.update', $master->id) }}">
                                    Редактировать
                                </a>
                                <form action="{{ route('director.employees.delete', $master->id) }}" method="POST" class="ajax-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color: #f44336; cursor:pointer;" onclick="return confirm('Удалить сотрудника?')">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">Мастера не найдены</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal-overlay" id="modal-employee-add">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Добавить сотрудника</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form action="{{ route('director.employees.store') }}" method="POST" class="ajax-form modal-form">
                @csrf
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email (для входа)</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Роль</label>
                    <select name="role" required>
                        <option value="admin">Администратор</option>
                        <option value="specialist">Мастер</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Салон</label>
                    <select name="salon_id" required>
                        @foreach($salons as $salon)
                            <option value="{{ $salon->id }}">{{ $salon->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Создать</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-employee-edit">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Редактировать сотрудника</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-form modal-form">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Роль</label>
                    <select name="role" required>
                        <option value="admin">Администратор</option>
                        <option value="specialist">Мастер</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Салон</label>
                    <select name="salon_id" required>
                        @foreach($salons as $salon)
                            <option value="{{ $salon->id }}">{{ $salon->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-modal]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
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
                        else {
                            const error = await response.json();
                            alert('Ошибка: ' + (error.message || 'Проверьте данные'));
                        }
                    } catch (e) { alert('Ошибка сети'); }
                    btn.disabled = false;
                });
            });
        });
    </script>
</body>
</html>
