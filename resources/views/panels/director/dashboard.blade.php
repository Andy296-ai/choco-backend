<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель Директора — Шоколад</title>
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

        /* Sidebar */
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

        /* Main Content */
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
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

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .card h3 {
            font-size: 14px;
            color: #888;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .card .value {
            font-size: 32px;
            font-weight: 600;
            color: var(--chocolate);
        }

        .table-container {
            margin-top: 40px;
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .chart-card {
            margin-top: 30px;
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .chart-container {
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}" class="active">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}">Настройки</a></li>
        </ul>

    </div>

    <div class="main-content">
        <div class="header">
            <h1>Панель Директора</h1>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <h3>Всего Салонов</h3>
                <div class="value">{{ $salonsCount }}</div>
            </div>
            <div class="card">
                <h3>Администраторы</h3>
                <div class="value">{{ $adminsCount }}</div>
            </div>
            <div class="card">
                <h3>Мастера</h3>
                <div class="value">{{ $mastersCount }}</div>
            </div>
            <div class="card">
                <h3>Выручка за месяц</h3>
                <div class="value">{{ $stats['revenue_month'] }}</div>
            </div>
        </div>

        <div class="chart-card">
            <h3 style="color: var(--chocolate); font-family: 'Playfair Display', serif;">Динамика выручки</h3>
            <div class="chart-container">
                <canvas id="dashboardRevenueChart"></canvas>
            </div>
        </div>

        <div class="table-container">
            <h3>Последние записи</h3>
            <table>
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Салон</th>
                        <th>Клиент</th>
                        <th>Услуга</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td>{{ $booking->start_time->format('d.m.Y H:i') }}</td>
                        <td>{{ $booking->salon->name ?? 'Салон удален' }}</td>
                        <td>{{ $booking->client->name ?? 'Гость' }}</td>
                        <td>{{ $booking->service->name ?? 'Услуга удалена' }}</td>
                        <td>{{ $booking->service ? number_format($booking->service->price, 0, '.', ' ') : '—' }} ₽</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'orange',
                                    'confirmed' => 'blue',
                                    'completed' => 'green',
                                    'cancelled' => 'red'
                                ];
                                $statusNames = [
                                    'pending' => 'Ожидает',
                                    'confirmed' => 'Подтверждена',
                                    'completed' => 'Выполнена',
                                    'cancelled' => 'Отменена'
                                ];
                            @endphp
                            <span style="color: {{ $statusColors[$booking->status] ?? 'black' }};">
                                {{ $statusNames[$booking->status] ?? $booking->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Записей не найдено</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Пагинация -->
            @if($recentBookings->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                @if($recentBookings->onFirstPage())
                    <span style="padding: 8px 15px; background: #f5f5f5; border-radius: 5px; color: #999;">← Назад</span>
                @else
                    <a href="{{ $recentBookings->previousPageUrl() }}" style="padding: 8px 15px; background: var(--gold); border-radius: 5px; color: var(--chocolate); text-decoration: none;">← Назад</a>
                @endif
                
                <span style="padding: 8px 15px; background: #f5f5f5; border-radius: 5px;">
                    Страница {{ $recentBookings->currentPage() }} из {{ $recentBookings->lastPage() }}
                </span>
                
                @if($recentBookings->hasMorePages())
                    <a href="{{ $recentBookings->nextPageUrl() }}" style="padding: 8px 15px; background: var(--gold); border-radius: 5px; color: var(--chocolate); text-decoration: none;">Вперёд →</a>
                @else
                    <span style="padding: 8px 15px; background: #f5f5f5; border-radius: 5px; color: #999;">Вперёд →</span>
                @endif
            </div>
            @endif
        </div>
    </div>

    <script>
        const ctx = document.getElementById('dashboardRevenueChart').getContext('2d');
        const monthlyData = @json($monthlyRevenue);
        
        new Chart(ctx, {
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
                    pointBackgroundColor: '#D4AF37',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>
