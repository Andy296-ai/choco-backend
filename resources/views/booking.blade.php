@extends('layouts.app')

@section('title', 'Онлайн запись — Шоколад')

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
                <div class="selection-grid">
                    <div class="selection-item" onclick="selectItem(this, 'salon', 'Вознесенская, 46')">
                        <h4>Шоколад на Вознесенской</h4>
                        <p>ул. Вознесенская, 46</p>
                    </div>
                    <div class="selection-item" onclick="selectItem(this, 'salon', 'Красной Армии, 251А')">
                        <h4>Шоколад на Красной Армии</h4>
                        <p>пр-т Красной Армии, 251А</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Service -->
            <div class="booking-step" id="step2">
                <h3>Выберите услугу</h3>
                <div class="selection-grid">
                    <div class="selection-item" onclick="selectItem(this, 'service', 'Стрижка женская')">
                        <h4>Стрижка женская</h4>
                        <p>от 1 500 ₽</p>
                    </div>
                    <div class="selection-item" onclick="selectItem(this, 'service', 'Маникюр')">
                        <h4>Маникюр + гель-лак</h4>
                        <p>1 800 ₽</p>
                    </div>
                    <div class="selection-item" onclick="selectItem(this, 'service', 'Окрашивание')">
                        <h4>Сложное окрашивание</h4>
                        <p>от 7 000 ₽</p>
                    </div>
                </div>
            </div>

            <!-- Step 3: Specialist -->
            <div class="booking-step" id="step3">
                <h3>Выберите специалиста</h3>
                <div class="selection-grid">
                    <div class="selection-item" onclick="selectItem(this, 'specialist', 'Елена К.')">
                        <h4>Елена К.</h4>
                        <p>Топ-стилист</p>
                    </div>
                    <div class="selection-item" onclick="selectItem(this, 'specialist', 'Ольга С.')">
                        <h4>Ольга С.</h4>
                        <p>Мастер маникюра</p>
                    </div>
                    <div class="selection-item" onclick="selectItem(this, 'specialist', 'Любой свободный')">
                        <h4>Любой свободный</h4>
                        <p>Подберем лучшего мастера</p>
                    </div>
                </div>
            </div>

            <!-- Step 4: Date & Time -->
            <div class="booking-step" id="step4">
                <h3>Выберите дату и время</h3>
                <div style="margin-top: 20px;">
                    <input type="date" style="width: 100%; padding: 10px; margin-bottom: 20px;">
                    <div class="selection-grid">
                        <div class="selection-item" onclick="selectItem(this, 'time', '10:00')">10:00</div>
                        <div class="selection-item" onclick="selectItem(this, 'time', '12:00')">12:00</div>
                        <div class="selection-item" onclick="selectItem(this, 'time', '14:00')">14:00</div>
                        <div class="selection-item" onclick="selectItem(this, 'time', '16:00')">16:00</div>
                    </div>
                </div>
            </div>

            <div class="btn-nav">
                <button type="button" class="btn-prev" onclick="prevStep()" id="prevBtn" style="display: none;">Назад</button>
                <button type="button" class="btn-next" onclick="nextStep()" id="nextBtn">Далее</button>
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
        salon: '',
        service: '',
        specialist: '',
        time: ''
    };

    function selectItem(element, field, value) {
        // Remove selected class from siblings
        const siblings = element.parentElement.children;
        for (let sibling of siblings) {
            sibling.classList.remove('selected');
        }
        element.classList.add('selected');
        formData[field] = value;
    }

    function nextStep() {
        if (currentStep < totalSteps) {
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');
            currentStep++;
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
            
            updateButtons();
        } else {
            alert('Запись успешно создана! Мы свяжемся с вами для подтверждения.');
            window.location.href = "{{ route('home') }}";
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            currentStep--;
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('completed');
            
            updateButtons();
        }
    }

    function updateButtons() {
        document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'block';
        document.getElementById('nextBtn').innerText = currentStep === totalSteps ? 'Записаться' : 'Далее';
    }
</script>
@endsection
