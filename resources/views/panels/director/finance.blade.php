<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Финансы — Шоколад</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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

        .content-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .content-card {
            background: var(--white);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(62, 39, 35, 0.05);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
        }

        .finance-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-item h4 {
            font-size: 14px;
            color: #888;
            margin: 0 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-item .amount {
            font-size: 28px;
            font-weight: 600;
            color: var(--chocolate);
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        h3 {
            font-family: 'Playfair Display', serif;
            color: var(--chocolate);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h3::before {
            content: '';
            display: inline-block;
            width: 30px;
            height: 2px;
            background: var(--gold);
        }

        .service-list {
            list-style: none;
            padding: 0;
        }

        .service-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .service-info .name {
            font-weight: 600;
            color: var(--chocolate);
            display: block;
        }

        .service-info .count {
            font-size: 12px;
            color: #888;
        }

        .service-revenue {
            font-weight: 600;
            color: var(--gold);
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}" class="active">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}">Настройки</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1 style="margin: 0; color: var(--chocolate); font-family: 'Playfair Display', serif;">Финансовая аналитика</h1>
                <p style="color: #888; margin-top: 5px;">Обзор доходов и эффективности сети</p>
            </div>
            <div style="background: var(--chocolate); color: var(--white); padding: 10px 20px; border-radius: 8px; font-size: 14px;">
                Период: {{ now()->startOfMonth()->format('d.m') }} - {{ now()->format('d.m.Y') }}
            </div>
        </div>

        <div class="content-card full-width">
            <div class="finance-summary">
                <div class="summary-item">
                    <h4>Выручка за месяц</h4>
                    <div class="amount">{{ number_format($monthRevenue, 0, '.', ' ') }} ₽</div>
                </div>
                <div class="summary-item">
                    <h4>Общая выручка</h4>
                    <div class="amount">{{ number_format($totalRevenue, 0, '.', ' ') }} ₽</div>
                </div>
                <div class="summary-item">
                    <h4>Общие расходы (35%)</h4>
                    <div class="amount" style="color: #d32f2f;">{{ number_format($expenses, 0, '.', ' ') }} ₽</div>
                </div>
                <div class="summary-item">
                    <h4>Чистая прибыль</h4>
                    <div class="amount" style="color: #2e7d32;">{{ number_format($profit, 0, '.', ' ') }} ₽</div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="content-card">
                <h3>Динамика выручки</h3>
                <div class="chart-container">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>

            <div class="content-card">
                <h3>Доля салонов в выручке</h3>
                <div class="chart-container">
                    <canvas id="salonDistributionChart"></canvas>
                </div>
            </div>

            <div class="content-card">
                <h3>Топ услуг по доходу</h3>
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
            </div>

            <div class="content-card">
                <h3>Популярные категории</h3>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data for Monthly Revenue Trend
        const monthlyData = @json($monthlyRevenue);
        const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
        new Chart(revenueTrendCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [{
                    label: 'Выручка (₽)',
                    data: monthlyData.map(d => d.revenue),
                    borderColor: '#3E2723',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#D4AF37'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Data for Salon Distribution
        const salonData = @json($salonRevenue);
        const salonDistCtx = document.getElementById('salonDistributionChart').getContext('2d');
        new Chart(salonDistCtx, {
            type: 'doughnut',
            data: {
                labels: salonData.map(s => s.name),
                datasets: [{
                    data: salonData.map(s => s.revenue),
                    backgroundColor: ['#3E2723', '#D4AF37', '#795548', '#8D6E63'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%'
            }
        });

        // Mock Data for Popular Categories (since we don't have categories in DB yet)
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: ['Стрижки', 'Окрашивание', 'Маникюр', 'Услуги лица'],
                datasets: [{
                    label: 'Записей',
                    data: [45, 32, 28, 15],
                    backgroundColor: '#D4AF37',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
