@extends('layouts.director')

@section('title', 'Панель Директора — Шоколад')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: var(--white);
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .card h3 {
        font-size: 12px;
        color: #888;
        margin: 0 0 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card .value {
        font-size: 32px;
        font-weight: 600;
        color: var(--chocolate);
    }

    .chart-card {
        background: var(--white);
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .chart-card h3 {
        font-family: 'Raleway', sans-serif;
        color: var(--chocolate);
        margin: 0 0 20px;
    }

    .chart-container { height: 280px; }

    .table-container {
        background: var(--white);
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .table-container h3 {
        font-family: 'Raleway', sans-serif;
        color: var(--chocolate);
        margin: 0 0 20px;
    }

    table { width: 100%; border-collapse: collapse; }

    th {
        text-align: left;
        padding: 12px 15px;
        border-bottom: 2px solid #f5f5f5;
        color: #888;
        font-size: 13px;
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #f5f5f5;
        font-size: 13px;
    }

    .status-badge {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
        .card .value { font-size: 24px; }
        .table-container { overflow-x: auto; }
        table { min-width: 560px; }
    }

    @media (max-width: 480px) {
        .dashboard-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endsection

@section('content')
    <div class="header">
        <h1 style="margin:0; font-family:'Raleway',serif; color:var(--chocolate);">Панель Директора</h1>
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
        <h3>Динамика выручки</h3>
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
                    <td>{{ $booking->salon->name ?? 'Салон удалён' }}</td>
                    <td>{{ $booking->client->name ?? 'Гость' }}</td>
                    <td>{{ $booking->service->name ?? 'Услуга удалена' }}</td>
                    <td>{{ $booking->service ? number_format($booking->service->price, 0, '.', ' ') : '—' }} ₽</td>
                    <td>
                        @php
                            $statusMap = [
                                'pending'   => ['Ожидает',      '#fff3e0', '#ef6c00'],
                                'confirmed' => ['Подтверждена', '#e8f5e9', '#2e7d32'],
                                'completed' => ['Выполнена',    '#e3f2fd', '#1565c0'],
                                'cancelled' => ['Отменена',     '#ffebee', '#c62828'],
                            ];
                            [$label, $bg, $fg] = $statusMap[$booking->status] ?? [$booking->status, '#f5f5f5', '#555'];
                        @endphp
                        <span class="status-badge" style="background:{{ $bg }};color:{{ $fg }};">{{ $label }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#888; padding:30px;">
                        Записей пока нет
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($recentBookings->hasPages())
            <div class="choco-pagination-wrap">{{ $recentBookings->links() }}</div>
        @endif
    </div>
@endsection

@section('scripts')
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
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection
