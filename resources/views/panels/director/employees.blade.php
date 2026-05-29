@extends('layouts.director')

@section('title', 'Сотрудники — Шоколад')

@section('styles')
<style>
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }

    th {
        text-align: left;
        padding: 12px 15px;
        border-bottom: 2px solid #f5f5f5;
        color: #888;
        font-size: 13px;
        font-weight: 600;
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #f5f5f5;
        font-size: 14px;
    }

    .section-title {
        font-family: 'Raleway', sans-serif;
        color: var(--chocolate);
        font-size: 20px;
        margin: 0 0 5px;
    }

    .section-title + table { margin-top: 10px; }

    .action-link {
        color: var(--gold);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        background: none;
        border: none;
        font-family: 'Montserrat', sans-serif;
        padding: 0;
    }

    .action-link:hover { text-decoration: underline; }

    .action-delete {
        color: #f44336;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        padding: 0;
    }

    .action-delete:hover { text-decoration: underline; }

    @media (max-width: 768px) {
        .content-card { overflow-x: auto; }
        table { min-width: 520px; }
    }
</style>
@endsection

@section('content')
    <div class="header">
        <h1 style="margin:0; font-family:'Raleway',serif; color:var(--chocolate);">Сотрудники</h1>
        <button class="btn-add" onclick="openModal('modal-employee-add')">+ Добавить сотрудника</button>
    </div>

    <div class="content-card">
        <h2 class="section-title">Администраторы</h2>
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
                        <div style="display:flex; gap:14px; align-items:center;">
                            <a class="action-link"
                               onclick="event.preventDefault(); openModal('modal-employee-edit');"
                               data-modal="modal-employee-edit"
                               data-edit="{{ json_encode($admin) }}"
                               data-action="{{ route('director.employees.update', $admin->id) }}">
                                Редактировать
                            </a>
                            <form action="{{ route('director.employees.delete', $admin->id) }}" method="POST" class="ajax-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete" onclick="return confirm('Удалить сотрудника?')">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#888; padding:24px;">
                        Администраторы не найдены
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="choco-pagination-wrap">
            {{ $admins->appends(['masters_page' => request('masters_page')])->links() }}
        </div>

        <h2 class="section-title" style="margin-top:40px;">Мастера</h2>
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
                        <div style="display:flex; gap:14px; align-items:center;">
                            <a class="action-link"
                               onclick="event.preventDefault(); openModal('modal-employee-edit');"
                               data-modal="modal-employee-edit"
                               data-edit="{{ json_encode($master) }}"
                               data-action="{{ route('director.employees.update', $master->id) }}">
                                Редактировать
                            </a>
                            <form action="{{ route('director.employees.delete', $master->id) }}" method="POST" class="ajax-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete" onclick="return confirm('Удалить сотрудника?')">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#888; padding:24px;">
                        Мастера не найдены
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="choco-pagination-wrap">
            {{ $masters->appends(['admins_page' => request('admins_page')])->links() }}
        </div>
    </div>
@endsection

@section('modals')
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
                    <label>Логин (для входа)</label>
                    <input type="text" name="login" required>
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
                    <label>Логин</label>
                    <input type="text" name="login" required>
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
                <div class="form-group">
                    <label>Новый пароль (оставьте пустым, чтобы не менять)</label>
                    <input type="password" name="password">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = document.getElementById(btn.getAttribute('data-modal'));
                if (!modal) return;
                modal.classList.add('active');
                if (btn.hasAttribute('data-edit')) {
                    const data = JSON.parse(btn.getAttribute('data-edit'));
                    const form = modal.querySelector('form');
                    form.action = btn.getAttribute('data-action');
                    Object.keys(data).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) input.value = data[key] ?? '';
                    });
                }
            });
        });
    });
</script>
@endsection
