@extends('layouts.director')

@section('title', 'Настройки — Шоколад')

@section('styles')
<style>
    .salon-card {
        background: var(--white);
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 24px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .salon-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .salon-card h2 {
        margin: 0 0 12px;
        font-family: 'Playfair Display', serif;
        color: var(--chocolate);
        font-size: 20px;
    }

    .salon-card p {
        margin: 5px 0;
        font-size: 14px;
        color: #555;
    }

    .salon-actions { display: flex; gap: 10px; flex-shrink: 0; }

    .btn-edit {
        background: none;
        border: 1px solid var(--gold);
        color: #888;
        padding: 8px 14px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-edit:hover { background: var(--gold); color: var(--chocolate); }

    .btn-delete {
        background: none;
        border: 1px solid #f44336;
        color: #f44336;
        padding: 8px 14px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-delete:hover { background: #f44336; color: white; }

    @media (max-width: 600px) {
        .salon-card { flex-direction: column; }
        .salon-actions { width: 100%; }
        .btn-edit, .btn-delete { flex: 1; text-align: center; }
    }
</style>
@endsection

@section('content')
    <div class="header">
        <h1 style="margin:0; font-family:'Playfair Display',serif; color:var(--chocolate);">Управление салонами</h1>
        <button class="btn-add" onclick="openModal('modal-salon-add')">+ Добавить салон</button>
    </div>

    @foreach($salons as $salon)
    <div class="salon-card">
        <div>
            <h2>{{ $salon->name }}</h2>
            <p><strong>Адрес:</strong> {{ $salon->address }}</p>
            <p><strong>Телефон:</strong> {{ $salon->phone }}</p>
            <p><strong>Координаты:</strong>
                @if($salon->latitude && $salon->longitude)
                    {{ $salon->latitude }}, {{ $salon->longitude }}
                @else
                    <span style="color:#f44336;">Не указаны (карта не будет работать)</span>
                @endif
            </p>
            @if($salon->description)
                <p><strong>Описание:</strong> {{ $salon->description }}</p>
            @endif
        </div>
        <div class="salon-actions">
            <button class="btn-edit"
                    data-modal="modal-salon-edit"
                    data-edit="{{ json_encode($salon) }}"
                    data-action="{{ route('director.salons.update', $salon->id) }}">
                Редактировать
            </button>
            <form action="{{ route('director.salons.delete', $salon->id) }}" method="POST" class="ajax-form" style="display:contents;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('Удалить салон?')">Удалить</button>
            </form>
        </div>
    </div>
    @endforeach

    @if($salons->isEmpty())
    <div style="text-align:center; padding:60px 20px; color:#888;">
        <div style="font-size:48px; margin-bottom:16px; opacity:0.3;">🏪</div>
        <h3 style="color:var(--chocolate); font-family:'Playfair Display',serif; margin-bottom:8px;">Салоны не добавлены</h3>
        <p>Нажмите «Добавить салон», чтобы создать первый</p>
    </div>
    @endif

    <div class="choco-pagination-wrap">{{ $salons->links() }}</div>
@endsection

@section('modals')
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
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Широта (Lat)</label>
                        <input type="number" step="any" name="latitude" placeholder="56.3075">
                    </div>
                    <div class="form-group">
                        <label>Долгота (Lng)</label>
                        <input type="number" step="any" name="longitude" placeholder="38.1345">
                    </div>
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
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Широта (Lat)</label>
                        <input type="number" step="any" name="latitude">
                    </div>
                    <div class="form-group">
                        <label>Долгота (Lng)</label>
                        <input type="number" step="any" name="longitude">
                    </div>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
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
