@extends('layouts.director')

@section('title', 'Финансы — Шоколад')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .finance-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .finance-header-row h1 {
        margin: 0;
        font-family: 'Raleway', sans-serif;
        color: var(--chocolate);
        font-size: 28px;
    }

    .finance-header-row p {
        color: #888;
        margin: 5px 0 0;
        font-size: 14px;
    }

    .filter-form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .filter-form label { color: #888; font-size: 13px; }

    .filter-form input[type="date"],
    .filter-form button,
    .filter-form a {
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
    }

    .filter-form input[type="date"] {
        border: 1px solid #ddd;
        color: var(--chocolate);
        outline: none;
    }

    .filter-form .btn-apply {
        background: var(--chocolate);
        color: var(--white);
        border: none;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .filter-form .btn-apply:hover { opacity: 0.85; }

    .filter-form .btn-pdf {
        background: #D32F2F;
        color: var(--white);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .content-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .full-width { grid-column: 1 / -1; }

    .content-card {
        background: var(--white);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(62,39,35,0.06);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .finance-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
    }

    .summary-item h4 {
        font-size: 12px;
        color: #888;
        margin: 0 0 8px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .summary-item .amount {
        font-size: 26px;
        font-weight: 600;
        color: var(--chocolate);
    }

    .chart-container { height: 280px; position: relative; }

    .card-title {
        font-family: 'Raleway', sans-serif;
        color: var(--chocolate);
        margin: 0 0 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title::before {
        content: '';
        display: inline-block;
        width: 28px;
        height: 2px;
        background: var(--gold);
        flex-shrink: 0;
    }

    .sort-btn {
        margin-left: auto;
        background: none;
        border: none;
        color: var(--gold);
        cursor: pointer;
        font-size: 12px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        padding: 0;
    }

    .service-list { list-style: none; padding: 0; margin: 0; }

    .service-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .service-item:last-child { border-bottom: none; }

    .service-info .name { font-weight: 600; color: var(--chocolate); display: block; }
    .service-info .count { font-size: 12px; color: #888; }
    .service-revenue { font-weight: 600; color: var(--gold); }

    /* ── Блок эффективности мастеров ── */
    .masters-layout {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 24px;
        align-items: flex-start;
    }

    .masters-chart-col {
        flex: 0 0 340px;
        position: relative;
        height: 260px;
    }

    .masters-table-wrap {
        flex: 1 1 300px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .masters-table {
        width: 100%;
        min-width: 320px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .masters-table thead th {
        background: #f9f9f9;
        padding: 10px 12px;
        text-align: left;
        font-size: 12px;
        color: #888;
        font-weight: 600;
        white-space: nowrap;
    }

    .masters-table tbody tr { border-bottom: 1px solid #eee; }

    .masters-table td { padding: 10px 12px; white-space: nowrap; }

    .level-badge {
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        white-space: nowrap;
    }

    .empty-state {
        text-align: center;
        padding: 30px;
        color: #888;
        font-size: 13px;
    }

    /* Ноутбук ≤ 1100px */
    @media (max-width: 1100px) {
        .content-grid { grid-template-columns: 1fr; }
    }

    /* Планшет и мобиле — чарт скрываем, таблица на всю ширину */
    @media (max-width: 900px) {
        .masters-chart-col { display: none; }
        .masters-table-wrap { flex: 0 0 100%; }
    }

    @media (max-width: 768px) {
        .finance-header-row { flex-direction: column; }
        .filter-form { width: 100%; }
        .finance-summary { grid-template-columns: repeat(2, 1fr); }
        .masters-table { font-size: 12px; }
        .masters-table thead th,
        .masters-table td { padding: 8px 10px; }
    }

    /* Телефон */
    @media (max-width: 480px) {
        .finance-summary { grid-template-columns: 1fr 1fr; }
        .summary-item .amount { font-size: 20px; }
        .masters-table { font-size: 11px; }
        .masters-table thead th,
        .masters-table td { padding: 7px 8px; }
        .level-badge { font-size: 9px; padding: 2px 5px; }
    }
</style>
@endsection

@section('content')
    <div class="finance-header-row">
        <div>
            <h1>Финансовая аналитика</h1>
            <p>Обзор доходов и эффективности сети</p>
        </div>
        <form method="GET" action="{{ route('director.finance') }}" class="filter-form">
            <label>С</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            <label>По</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            <button type="submit" class="btn-apply">Применить</button>
            <input type="hidden" name="service_sort"  id="service_sort"  value="{{ $serviceSort }}">
            <input type="hidden" name="category_sort" id="category_sort" value="{{ $categorySort }}">
            <input type="hidden" name="master_sort"   id="master_sort"   value="{{ $masterSort }}">
            <a href="{{ route('director.finance.export-pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
               class="btn-apply btn-pdf">PDF Отчёт</a>
        </form>
    </div>

    <!-- Сводка -->
    <div class="content-card full-width" style="margin-bottom:25px;">
        <div class="finance-summary">
            <div class="summary-item">
                <h4>Выручка за период</h4>
                <div class="amount">{{ number_format($monthRevenue, 0, '.', ' ') }} ₽</div>
                <p style="font-size:12px;color:#888;margin:4px 0 0;">{{ $startDate->format('d.m.Y') }} — {{ $endDate->format('d.m.Y') }}</p>
            </div>
            <div class="summary-item">
                <h4>Общая выручка</h4>
                <div class="amount">{{ number_format($totalRevenue, 0, '.', ' ') }} ₽</div>
            </div>
            <div class="summary-item">
                <h4>Расходы (35%)</h4>
                <div class="amount" style="color:#d32f2f;">{{ number_format($expenses, 0, '.', ' ') }} ₽</div>
            </div>
            <div class="summary-item">
                <h4>Чистая прибыль</h4>
                <div class="amount" style="color:#2e7d32;">{{ number_format($profit, 0, '.', ' ') }} ₽</div>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Динамика выручки -->
        <div class="content-card">
            <h3 class="card-title">Динамика выручки</h3>
            <div class="chart-container">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        <!-- Доля салонов -->
        <div class="content-card">
            <h3 class="card-title">Доля салонов в выручке</h3>
            <div class="chart-container">
                <canvas id="salonDistributionChart"></canvas>
            </div>
        </div>

        <!-- Услуги по доходу -->
        <div class="content-card">
            <h3 class="card-title">
                Услуги по доходу
                <button type="button" class="sort-btn" onclick="toggleSort('service')">
                    {{ $serviceSort === 'desc' ? 'Топ ↑' : 'Анти-топ ↓' }}
                </button>
            </h3>
            @if($topServices->isEmpty())
                <div class="empty-state">Нет данных за период</div>
            @else
                <ul class="service-list">
                    @foreach($topServices as $service)
                    <li class="service-item">
                        <div class="service-info">
                            <span class="name">{{ $service['name'] }}</span>
                            <span class="count">{{ $service['count'] }} записей</span>
                        </div>
                        <div class="service-revenue">{{ number_format($service['revenue'], 0, '.', ' ') }} ₽</div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Популярные категории -->
        <div class="content-card">
            <h3 class="card-title">
                Популярные категории
                <button type="button" class="sort-btn" onclick="toggleSort('category')">
                    {{ $categorySort === 'desc' ? 'Популярные ↑' : 'Редкие ↓' }}
                </button>
            </h3>
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        {{-- TODO: блок «Эффективность мастеров» временно скрыт — требует доработки адаптивности чарта
        <div class="content-card full-width">
            <h3 class="card-title">
                Эффективность мастеров
                <button type="button" class="sort-btn" onclick="toggleSort('master')">
                    {{ $masterSort === 'desc' ? 'Лучшие ↑' : 'Отстающие ↓' }}
                </button>
            </h3>

            @if(empty($masterPerformance) || count($masterPerformance) === 0)
                <div class="empty-state">Нет данных за выбранный период</div>
            @else
                <div class="masters-layout">
                    <div class="masters-chart-col">
                        <canvas id="masterPerformanceChart"></canvas>
                    </div>
                    <div class="masters-table-wrap">
                        <table class="masters-table">
                            <thead>
                                <tr>
                                    <th>Мастер</th>
                                    <th>Записи</th>
                                    <th>Выручка</th>
                                    <th>Уровень</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($masterPerformance as $master)
                                <tr>
                                    <td>{{ $master['name'] }}</td>
                                    <td style="text-align:center;">{{ $master['count'] }}</td>
                                    <td style="text-align:right; font-weight:600;">{{ number_format($master['revenue'], 0, '.', ' ') }} ₽</td>
                                    <td style="text-align:center;">
                                        <span class="level-badge" style="
                                            background:{{ $master['level'] === 'Senior' ? '#C8E6C9' : ($master['level'] === 'Middle' ? '#FFF9C4' : '#F5F5F5') }};
                                            color:{{ $master['level'] === 'Senior' ? '#2E7D32' : ($master['level'] === 'Middle' ? '#FBC02D' : '#757575') }};">
                                            {{ $master['level'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        --}}
    </div>
@endsection

@section('scripts')
<script>
    const monthlyData = @json($monthlyRevenue);
    new Chart(document.getElementById('revenueTrendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Выручка (₽)',
                data: monthlyData.map(d => d.revenue),
                borderColor: '#3E2723',
                backgroundColor: 'rgba(212,175,55,0.1)',
                fill: true, tension: 0.4, borderWidth: 3,
                pointBackgroundColor: '#D4AF37'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
        }
    });

    const salonData = @json($salonRevenue);
    new Chart(document.getElementById('salonDistributionChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: salonData.map(s => s.name),
            datasets: [{ data: salonData.map(s => s.revenue),
                backgroundColor: ['#3E2723','#D4AF37','#795548','#8D6E63'],
                borderWidth: 2, borderColor: '#ffffff' }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
    });

    const categoryData = @json($categoryData);
    new Chart(document.getElementById('categoryChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(categoryData),
            datasets: [{ label: 'Записей', data: Object.values(categoryData).map(c => c.count),
                backgroundColor: '#D4AF37', borderRadius: 5 }]
        },
        options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y',
            plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
    });

    {{-- TODO: JS чарта «Эффективность мастеров» — раскомментировать вместе с блоком выше
    @if(!empty($masterPerformance) && count($masterPerformance) > 0)
    const masterData = @json($masterPerformance);
    const masterChart = new Chart(document.getElementById('masterPerformanceChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: masterData.map(m => m.name),
            datasets: [
                { label: 'Выручка (x10 ₽)', data: masterData.map(m => m.revenue / 10), backgroundColor: '#3E2723', borderRadius: 5 },
                { label: 'Записи', data: masterData.map(m => m.count), backgroundColor: '#D4AF37', borderRadius: 5 }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
            scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
        }
    });
    window.addEventListener('load', () => masterChart.resize());
    @endif
    --}}

    function toggleSort(type) {
        const input = document.getElementById(type + '_sort');
        input.value = input.value === 'desc' ? 'asc' : 'desc';
        input.closest('form').submit();
    }
</script>
@endsection
