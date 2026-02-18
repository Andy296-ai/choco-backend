@extends('layouts.app')

@section('title', 'Онлайн запись — Шоколад')

@section('breadcrumbs')
<div class="container">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Главная</a>
        <span>/</span>
        <span>Онлайн запись</span>
    </div>
</div>
@endsection

@section('styles')
<style>
    .page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .booking-container {
        max-width: 800px;
        margin: 50px auto;
        background: var(--white);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #eee;
        z-index: 1;
    }

    .step {
        width: 30px;
        height: 30px;
        background: #eee;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .step.active {
        background: var(--gold);
        color: var(--chocolate);
    }

    .step.completed {
        background: var(--chocolate);
        color: var(--white);
    }

    .booking-step {
        display: none;
    }

    .booking-step.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    .selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .selection-item {
        border: 2px solid #eee;
        padding: 20px;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s ease;
    }

    .selection-item:hover, .selection-item.selected {
        border-color: var(--gold);
        background: rgba(212, 175, 55, 0.05);
    }

    .btn-nav {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
    }

    .btn-next, .btn-prev {
        padding: 12px 30px;
        border-radius: 2px;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
        border: none;
    }

    .btn-next { background: var(--gold); color: var(--chocolate); }
    .btn-prev { background: #eee; color: #888; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Онлайн запись</h1>
    </div>
</div>

<div class="container">
    <div class="booking-container">
        <div class="step-indicator">
            <div class="step active" data-step="1">1</div>
            <div class="step" data-step="2">2</div>
            <div class="step" data-step="3">3</div>
            <div class="step" data-step="4">4</div>
        </div>

        <form id="bookingForm">
            <!-- Step 1: Salon -->
            <div class="booking-step active" id="step1">
                <h3>Выберите салон</h3>
                <div class="selection-grid" id="salonGrid">
                    <p>Загрузка салонов...</p>
                </div>
            </div>

            <!-- Step 2: Service -->
            <div class="booking-step" id="step2">
                <h3>Выберите услугу</h3>
                <div class="selection-grid" id="serviceGrid">
                    <p>Пожалуйста, выберите сначала салон.</p>
                </div>
            </div>

            <!-- Step 3: Specialist -->
            <div class="booking-step" id="step3">
                <h3>Выберите специалиста</h3>
                <div class="selection-grid" id="specialistGrid">
                    <p>Пожалуйста, выберите сначала услугу.</p>
                </div>
            </div>

            <!-- Step 4: Date & Time -->
            <div class="booking-step" id="step4">
                <h3>Выберите дату и время</h3>
                <div style="margin-top: 20px;">
                    <input type="date" id="bookingDate" style="width: 100%; padding: 10px; margin-bottom: 20px;" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                    <div class="selection-grid" id="timeGrid">
                        <p>Пожалуйста, выберите дату.</p>
                    </div>
                </div>
            </div>

            <div class="btn-nav">
                <button type="button" class="btn-prev" onclick="prevStep()" id="prevBtn" style="display: none;">Назад</button>
                <button type="button" class="btn-next" onclick="nextStep()" id="nextBtn" disabled>Далее</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;
    const formData = {
        salon_id: '',
        service_id: '',
        specialist_id: '',
        date: '{{ date('Y-m-d') }}',
        time: ''
    };

    document.addEventListener('DOMContentLoaded', () => {
        loadSalons();
        
        document.getElementById('bookingDate').addEventListener('change', (e) => {
            formData.date = e.target.value;
            if (formData.specialist_id) {
                loadTimeSlots();
            }
        });
    });

    async function loadSalons() {
        const grid = document.getElementById('salonGrid');
        try {
            const response = await fetch('{{ route('api.salons') }}');
            const salons = await response.json();
            
            grid.innerHTML = salons.map(salon => `
                <div class="selection-item" onclick="selectItem(this, 'salon_id', ${salon.id})">
                    <h4>${salon.name}</h4>
                    <p>${salon.address}</p>
                </div>
            `).join('');
        } catch (error) {
            grid.innerHTML = '<p>Ошибка загрузки салонов. Пожалуйста, обновите страницу.</p>';
        }
    }

    async function loadServices() {
        const grid = document.getElementById('serviceGrid');
        grid.innerHTML = '<p>Загрузка услуг...</p>';
        try {
            const response = await fetch('{{ route('api.services') }}');
            const services = await response.json();
            
            grid.innerHTML = services.map(service => `
                <div class="selection-item" onclick="selectItem(this, 'service_id', ${service.id})">
                    <h4>${service.name}</h4>
                    <p>${service.price} ₽ • ${service.duration_minutes} мин</p>
                </div>
            `).join('');
        } catch (error) {
            grid.innerHTML = '<p>Ошибка загрузки услуг.</p>';
        }
    }

    async function loadSpecialists() {
        const grid = document.getElementById('specialistGrid');
        grid.innerHTML = '<p>Загрузка специалистов...</p>';
        try {
            const response = await fetch(`{{ route('api.specialists') }}?salon_id=${formData.salon_id}`);
            const specialists = await response.json();
            
            if (specialists.length === 0) {
                grid.innerHTML = '<p>В этом салоне пока нет специалистов для выбранной услуги.</p>';
                return;
            }

            grid.innerHTML = specialists.map(specialist => `
                <div class="selection-item" onclick="selectItem(this, 'specialist_id', ${specialist.id})">
                    <h4>${specialist.name}</h4>
                    <p>${specialist.role === 'specialist' ? 'Мастер' : specialist.role}</p>
                </div>
            `).join('') + `
                <div class="selection-item" onclick="selectItem(this, 'specialist_id', 'any')">
                    <h4>Любой свободный</h4>
                    <p>Подберем лучшего мастера</p>
                </div>
            `;
        } catch (error) {
            grid.innerHTML = '<p>Ошибка загрузки специалистов.</p>';
        }
    }

    async function loadTimeSlots() {
        const grid = document.getElementById('timeGrid');
        grid.innerHTML = '<p>Загрузка времени...</p>';
        try {
            const response = await fetch(`{{ route('api.slots') }}?specialist_id=${formData.specialist_id}&date=${formData.date}`);
            const slots = await response.json();
            
            grid.innerHTML = slots.map(slot => `
                <div class="selection-item" onclick="selectItem(this, 'time', '${slot}')">${slot}</div>
            `).join('');
        } catch (error) {
            grid.innerHTML = '<p>Ошибка загрузки времени.</p>';
        }
    }

    function selectItem(element, field, value) {
        // Remove selected class from siblings
        const siblings = element.parentElement.children;
        for (let sibling of siblings) {
            sibling.classList.remove('selected');
        }
        element.classList.add('selected');
        formData[field] = value;
        
        // Logical follow-ups
        if (field === 'salon_id') loadServices();
        if (field === 'service_id') loadSpecialists();
        if (field === 'specialist_id' || (field === 'time' && formData.specialist_id)) {
            if (field === 'specialist_id') loadTimeSlots();
        }

        document.getElementById('nextBtn').disabled = false;
    }

    function nextStep() {
        // Validation for each step
        if (currentStep === 1 && !formData.salon_id) return;
        if (currentStep === 2 && !formData.service_id) return;
        if (currentStep === 3 && !formData.specialist_id) return;

        if (currentStep < totalSteps) {
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');
            currentStep++;
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
            
            // Disable "Next" until item in new step is selected
            if (currentStep === 2 && !formData.service_id) document.getElementById('nextBtn').disabled = true;
            if (currentStep === 3 && !formData.specialist_id) document.getElementById('nextBtn').disabled = true;
            if (currentStep === 4 && !formData.time) document.getElementById('nextBtn').disabled = true;

            updateButtons();
        } else {
            submitBooking();
        }
    }

    async function submitBooking() {
        if (!formData.time) {
            alert('Пожалуйста, выберите время.');
            return;
        }

        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        
        nextBtn.disabled = true;
        prevBtn.disabled = true;
        nextBtn.innerText = 'Обработка...';

        try {
            const response = await fetch('{{ route('booking.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok) {
                alert(result.message || 'Запись успешно создана!');
                window.location.href = "{{ route('home') }}";
            } else {
                alert(result.error || result.message || 'Произошла ошибка при создании записи.');
                nextBtn.disabled = false;
                prevBtn.disabled = false;
                nextBtn.innerText = 'Записаться';
            }
        } catch (error) {
            alert('Произошла ошибка при создании записи. Попробуйте позже.');
            nextBtn.disabled = false;
            prevBtn.disabled = false;
            nextBtn.innerText = 'Записаться';
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            currentStep--;
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('completed');
            
            document.getElementById('nextBtn').disabled = false;
            updateButtons();
        }
    }

    function updateButtons() {
        document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'block';
        document.getElementById('nextBtn').innerText = currentStep === totalSteps ? 'Записаться' : 'Далее';
    }
</script>
@endsection
