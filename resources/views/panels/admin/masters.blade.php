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

        .master-card h4 {
            margin: 10px 0 5px;
            color: var(--chocolate);
        }

        .master-card p {
            font-size: 12px;
            color: #888;
            margin-bottom: 15px;
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
        }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Мастера</h1>
    </div>

    <!-- Форма поиска и фильтров -->
    <div class="content-card" style="margin-bottom: 20px; padding: 20px;">
        <form method="GET" action="{{ route('admin.masters') }}" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
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
            
            <a href="{{ route('admin.masters') }}" style="background: #f5f5f5; color: #666; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: 600; display: inline-block;">
                Сбросить
            </a>
        </form>
    </div>

    <div class="content-card">
        <div class="master-grid">
            @forelse($masters as $master)
            <div class="master-card">
                <h4>{{ $master->name }}</h4>
                <p>{{ $master->telegram_username ? '@' . $master->telegram_username : 'Специалист' }}</p>
                <button class="btn-schedule" onclick="editSchedule({{ $master->id }}, '{{ $master->name }}')">График работы</button>
                <button class="btn-schedule" style="background: #f5f5f5; margin-top: 5px;" onclick="addAbsence({{ $master->id }}, '{{ $master->name }}')">Отметить отсутствие</button>
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
            
            // Fix checkboxes not sending '0' when unchecked is tricky with FormData.
            // The hidden inputs setup above handles this, but let's be sure.
            
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
                else {
                    alert(data.message || 'Сохранено успешно');
                    location.reload();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Произошла ошибка при сохранении');
            });
        }
    </script>
@endsection



