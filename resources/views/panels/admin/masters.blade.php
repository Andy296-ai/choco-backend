@extends('layouts.admin')

@section('title', 'Мастера — Шоколад')

@section('styles')
    <style>
        .master-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .master-card {
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        /* ── Аватар ── */
        .master-avatar {
            width: 60px; height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--chocolate), #5D4037);
            color: var(--gold);
            font-size: 22px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            flex-shrink: 0;
        }

        .master-card h4 {
            margin: 0 0 4px;
            color: var(--chocolate);
            font-size: 15px;
        }

        /* ── Инфо-строки ── */
        .master-info-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .master-info-row a { color: #666; text-decoration: none; }
        .master-info-row a:hover { color: var(--chocolate); text-decoration: underline; }

        .master-info-icon { font-size: 13px; flex-shrink: 0; }

        .master-divider {
            border: none; border-top: 1px solid #f0f0f0;
            margin: 14px 0 12px;
        }

        .btn-schedule {
            background: var(--gold);
            color: var(--chocolate);
            border: none;
            padding: 8px 15px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-bottom: 5px;
        }

        /* ── Список отсутствий ── */
        .absence-list {
            margin-top: 14px;
            border-top: 1px solid #f0f0f0;
            padding-top: 12px;
            text-align: left;
        }

        .absence-list-title {
            font-size: 11px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .absence-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            background: #fff8f8;
            border: 1px solid #ffe0e0;
            border-radius: 6px;
            padding: 8px 10px;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .absence-item.future { background: #fff8f0; border-color: #ffe0b2; }
        .absence-item.past   { background: #f5f5f5; border-color: #e0e0e0; opacity: 0.7; }

        .absence-dates { font-weight: 600; color: var(--chocolate); }
        .absence-reason { color: #888; font-size: 11px; margin-top: 2px; }

        .btn-absence-del {
            background: none;
            border: none;
            cursor: pointer;
            color: #e53935;
            font-size: 16px;
            line-height: 1;
            padding: 0 2px;
            flex-shrink: 0;
            opacity: 0.6;
            transition: opacity 0.15s;
        }
        .btn-absence-del:hover { opacity: 1; }

        .absence-empty {
            font-size: 12px;
            color: #bbb;
            text-align: center;
            padding: 4px 0;
        }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Мастера</h1>
    </div>

    <!-- Поиск -->
    <div class="content-card" style="margin-bottom: 20px; padding: 20px;">
        <form method="GET" action="{{ route('admin.masters') }}" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display:block; font-size:12px; font-weight:600; color:var(--chocolate); margin-bottom:6px;">Поиск мастера</label>
                <input type="text" name="search" placeholder="Имя, телефон или email..."
                       value="{{ request('search') }}"
                       style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; font-family:'Montserrat',sans-serif; font-size:13px; outline:none;">
            </div>
            <button type="submit" style="background:var(--gold); color:var(--chocolate); border:none; padding:10px 20px; border-radius:6px; font-family:'Montserrat',sans-serif; font-weight:600; font-size:13px; cursor:pointer;">
                Найти
            </button>
            @if(request('search'))
            <a href="{{ route('admin.masters') }}" style="background:#f5f5f5; color:#666; text-decoration:none; padding:10px 16px; border-radius:6px; font-family:'Montserrat',sans-serif; font-weight:600; font-size:13px; display:inline-block;">
                Сбросить
            </a>
            @endif
        </form>
    </div>

    <div class="content-card">
        <div class="master-grid">
            @forelse($masters as $master)
            <div class="master-card">
                {{-- Аватар --}}
                <div class="master-avatar">{{ mb_strtoupper(mb_substr($master->name, 0, 1)) }}</div>

                <h4>{{ $master->name }}</h4>

                {{-- Контактная информация --}}
                @if($master->phone)
                    <div class="master-info-row">
                        <span class="master-info-icon">📞</span>
                        <a href="tel:{{ $master->phone }}">{{ $master->phone }}</a>
                    </div>
                @endif
                @if($master->email && !str_ends_with($master->email, '@client.local'))
                    <div class="master-info-row">
                        <span class="master-info-icon">✉️</span>
                        <a href="mailto:{{ $master->email }}">{{ $master->email }}</a>
                    </div>
                @endif
                @if($master->telegram_username)
                    <div class="master-info-row">
                        <span class="master-info-icon">✈️</span>
                        <a href="https://t.me/{{ $master->telegram_username }}" target="_blank">@{{ $master->telegram_username }}</a>
                    </div>
                @endif
                @if(!$master->phone && !$master->email && !$master->telegram_username)
                    <div class="master-info-row" style="color:#bbb;">Контакты не указаны</div>
                @endif

                <hr class="master-divider">

                <button class="btn-schedule" onclick="editSchedule({{ $master->id }}, '{{ $master->name }}')">График работы</button>
                <button class="btn-schedule" style="background: #f5f5f5; color: var(--chocolate);" onclick="addAbsence({{ $master->id }}, '{{ $master->name }}')">+ Отметить отсутствие</button>

                {{-- Список отсутствий --}}
                <div class="absence-list">
                    <div class="absence-list-title">Отсутствия</div>
                    @forelse($master->absences as $absence)
                        @php
                            $now = now()->startOfDay();
                            $end = \Carbon\Carbon::parse($absence->end_date);
                            $start = \Carbon\Carbon::parse($absence->start_date);
                            $cls = $end->lt($now) ? 'past' : ($start->gt($now) ? 'future' : '');
                        @endphp
                        <div class="absence-item {{ $cls }}" id="absence-{{ $absence->id }}">
                            <div>
                                <div class="absence-dates">
                                    {{ $start->format('d.m.Y') }} — {{ $end->format('d.m.Y') }}
                                </div>
                                @if($absence->reason)
                                    <div class="absence-reason">{{ $absence->reason }}</div>
                                @endif
                            </div>
                            <button class="btn-absence-del" onclick="deleteAbsence({{ $absence->id }})" title="Удалить">×</button>
                        </div>
                    @empty
                        <div class="absence-empty">Нет отмеченных отсутствий</div>
                    @endforelse
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; color: #888;">
                <p>В этом салоне мастера пока не назначены</p>
            </div>
            @endforelse
        </div>
        
        <div style="margin-top: 20px; text-align: center;">
            {{ $masters->links() }}
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal-overlay" id="modal-master-schedule">
        <div class="modal-container" style="max-width: 600px;">
            <div class="modal-header">
                <h3>График работы — <span id="schedule-master-name"></span></h3>
                <button class="close-modal" onclick="closeModal('modal-master-schedule')">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-form modal-form" id="form-master-schedule" onsubmit="handleFormSubmit(event)">
                @csrf
                <div id="schedule-days-container">
                    <p>Загрузка...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-master-schedule')">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-master-absence">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Отметить отсутствие — <span id="absence-master-name"></span></h3>
                <button class="close-modal" onclick="closeModal('modal-master-absence')">&times;</button>
            </div>
            <form action="" method="POST" class="ajax-form modal-form" id="form-master-absence" onsubmit="handleFormSubmit(event)">
                @csrf
                <div class="form-group">
                    <label>Дата начала</label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label>Дата окончания</label>
                    <input type="date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label>Причина (необязательно)</label>
                    <textarea name="reason" rows="2"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-master-absence')">Отмена</button>
                    <button type="submit" class="btn-confirm">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const daysOfWeek = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];

        function editSchedule(masterId, masterName) {
            document.getElementById('schedule-master-name').textContent = masterName;
            const form = document.getElementById('form-master-schedule');
            form.action = `{{ url('/admin/masters') }}/${masterId}/schedule`;
            document.getElementById('schedule-days-container').innerHTML = '<p>Загрузка...</p>';
            openModal('modal-master-schedule');

            fetch(`{{ url('/admin/masters') }}/${masterId}/schedule`)
                .then(res => res.json())
                .then(data => {
                    const schedules = data.schedules || [];
                    let html = '';
                    
                    for (let i = 0; i < 7; i++) {
                        const existing = schedules.find(s => s.day_of_week === i);
                        const isWorking = existing ? existing.is_working : false;
                        const start = existing && existing.start_time ? existing.start_time.substring(0, 5) : '09:00';
                        const end = existing && existing.end_time ? existing.end_time.substring(0, 5) : '18:00';
                        
                        html += `
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;">
                                <div style="width: 120px;">
                                    <input type="hidden" name="schedules[${i}][day_of_week]" value="${i}">
                                    <label style="display:flex; align-items:center; cursor:pointer;">
                                        <input type="checkbox" name="schedules[${i}][is_working]" value="1" ${isWorking ? 'checked' : ''} onchange="toggleTimeInputs(this, ${i})" style="margin-right: 8px;">
                                        <input type="hidden" name="schedules[${i}][is_working]" value="0" ${!isWorking ? 'disabled' : ''} id="hidden-working-${i}">
                                        ${daysOfWeek[i]}
                                    </label>
                                </div>
                                <div style="display: flex; gap: 10px; opacity: ${isWorking ? '1' : '0.5'};" id="time-inputs-${i}">
                                    <input type="time" name="schedules[${i}][start_time]" value="${start}" ${!isWorking ? 'disabled' : ''} style="padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                    <span>-</span>
                                    <input type="time" name="schedules[${i}][end_time]" value="${end}" ${!isWorking ? 'disabled' : ''} style="padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                </div>
                            </div>
                        `;
                    }
                    
                    document.getElementById('schedule-days-container').innerHTML = html;
                    
                    // Add listeners to update hidden inputs correctly
                    document.querySelectorAll('input[type="checkbox"][name$="[is_working]"]').forEach(cb => {
                        cb.addEventListener('change', function() {
                            const index = this.name.match(/\d+/)[0];
                            const hiddenInput = document.getElementById(`hidden-working-${index}`);
                            if (this.checked) {
                                hiddenInput.disabled = true; // don't send '0' if '1' is sent
                            } else {
                                hiddenInput.disabled = false; // send '0' if unchecked
                                // Also remove disabled from the hidden input to ensure it gets submitted
                                hiddenInput.removeAttribute('disabled');
                            }
                        });
                        // trigger change to set initial state correctly
                        cb.dispatchEvent(new Event('change'));
                    });
                })
                .catch(err => {
                    document.getElementById('schedule-days-container').innerHTML = '<p style="color:red;">Ошибка загрузки</p>';
                });
        }

        function toggleTimeInputs(checkbox, index) {
            const container = document.getElementById(`time-inputs-${index}`);
            const inputs = container.querySelectorAll('input[type="time"]');
            
            if (checkbox.checked) {
                container.style.opacity = '1';
                inputs.forEach(input => input.disabled = false);
            } else {
                container.style.opacity = '0.5';
                inputs.forEach(input => input.disabled = true);
            }
        }

        function addAbsence(masterId, masterName) {
            document.getElementById('absence-master-name').textContent = masterName;
            const form = document.getElementById('form-master-absence');
            form.action = `{{ url('/admin/masters') }}/${masterId}/absence`;
            form.reset();
            openModal('modal-master-absence');
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('[type=submit]');
            if (btn) { btn.disabled = true; btn.textContent = 'Сохранение...'; }

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    showToast(data.error, 'error');
                    if (btn) { btn.disabled = false; btn.textContent = 'Сохранить'; }
                } else {
                    showToast(data.message || 'Сохранено успешно', 'success');
                    setTimeout(() => location.reload(), 900);
                }
            })
            .catch(() => {
                showToast('Произошла ошибка при сохранении', 'error');
                if (btn) { btn.disabled = false; btn.textContent = 'Сохранить'; }
            });
        }

        async function deleteAbsence(id) {
            if (!confirm('Удалить это отсутствие?')) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const res = await fetch(`{{ url('/admin/absences') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (res.ok) {
                    showToast(data.message || 'Отсутствие удалено', 'success');
                    const el = document.getElementById(`absence-${id}`);
                    if (el) {
                        const list = el.closest('.absence-list');
                        el.style.transition = 'opacity 0.3s';
                        el.style.opacity = '0';
                        setTimeout(() => {
                            el.remove();
                            if (list && list.querySelectorAll('.absence-item').length === 0) {
                                const empty = document.createElement('div');
                                empty.className = 'absence-empty';
                                empty.textContent = 'Нет отмеченных отсутствий';
                                list.appendChild(empty);
                            }
                        }, 300);
                    }
                } else {
                    showToast(data.error || 'Ошибка при удалении', 'error');
                }
            } catch {
                showToast('Ошибка сети', 'error');
            }
        }
    </script>
@endsection



