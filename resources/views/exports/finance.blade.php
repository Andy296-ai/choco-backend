<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Финансовый Отчет — Шоколад Лайф</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'DejaVu Sans', serif; color: #1a1a1a; line-height: 1.4; font-size: 11pt; }
        
        .doc-header { border-bottom: 2px solid #3e2723; padding-bottom: 20px; margin-bottom: 30px; }
        .doc-header table { width: 100%; border-collapse: collapse; }
        .logo-box { width: 60%; vertical-align: top; }
        .info-box { width: 40%; text-align: right; font-size: 9pt; color: #666; vertical-align: top; }
        
        .company-name { font-size: 22pt; font-weight: bold; color: #3e2723; margin: 0; }
        .company-tagline { font-size: 10pt; color: #d4af37; letter-spacing: 2px; text-transform: uppercase; margin-top: 5px; }
        
        .doc-title { text-align: center; font-size: 16pt; font-weight: bold; margin: 40px 0 20px; text-transform: uppercase; letter-spacing: 1px; }
        .doc-meta { text-align: center; font-size: 10pt; color: #666; margin-bottom: 40px; }
        
        .stats-grid { width: 100%; margin-bottom: 40px; }
        .stats-grid td { padding: 15px; border: 1px solid #e0e0e0; text-align: center; }
        .stats-label { font-size: 8pt; color: #888; text-transform: uppercase; display: block; margin-bottom: 5px; }
        .stats-value { font-size: 14pt; font-weight: bold; color: #3e2723; }
        .stats-value.profit { color: #2e7d32; }
        .stats-value.expenses { color: #c62828; }
        
        .section-title { font-size: 12pt; font-weight: bold; border-left: 5px solid #d4af37; padding-left: 10px; margin: 30px 0 15px; text-transform: uppercase; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.data-table th { background: #f5f5f5; text-align: left; padding: 10px; border-bottom: 1px solid #333; font-size: 9pt; text-transform: uppercase; }
        table.data-table td { padding: 10px; border-bottom: 1px solid #eee; font-size: 9pt; }
        
        /* SVG Charts */
        .chart-row { margin-bottom: 15px; }
        .chart-label { font-size: 8pt; color: #555; margin-bottom: 3px; }
        .chart-bar-bg { background: #f0f0f0; height: 12px; border-radius: 2px; width: 100%; position: relative; }
        .chart-bar-fill { background: #d4af37; height: 100%; border-radius: 2px; }
        .chart-bar-fill.secondary { background: #3e2723; }
        .chart-value { position: absolute; right: -50px; top: -2px; font-size: 8pt; font-weight: bold; }

        .signature-block { margin-top: 60px; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-cell { width: 50%; vertical-align: top; padding-top: 40px; }
        .signature-line { border-bottom: 1px solid #333; width: 80%; margin-bottom: 5px; }
        .signature-label { font-size: 8pt; color: #888; }
        
        .stamp-box { position: relative; height: 120px; text-align: center; }
        .official-stamp {
            width: 100px;
            height: 100px;
            border: 2px dashed #2a45a3;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.6;
            transform: rotate(-15deg);
            color: #2a45a3;
            text-align: center;
            padding-top: 20px;
            font-size: 8pt;
            font-weight: bold;
        }

        .footer { position: fixed; bottom: -1cm; width: 100%; text-align: center; font-size: 7pt; color: #bbb; }
    </style>
</head>
<body>
    <div class="doc-header">
        <table>
            <tr>
                <td class="logo-box">
                    @if($logoBase)
                        <img src="{{ $logoBase }}" style="max-height: 50px;">
                    @else
                        <h1 class="company-name">CHOCO LIFE</h1>
                    @endif
                    <div class="company-tagline">Сеть салонов красоты премиум-класса</div>
                </td>
                <td class="info-box">
                    ОТЧЕТ № {{ date('Y-m-d') }}-{{ rand(10,99) }}<br>
                    ДАТА: {{ now()->format('d.m.Y H:i') }}<br>
                    СТАРТ: {{ $startDate->format('d.m.Y') }}<br>
                    ЗАВЕРШЕНИЕ: {{ $endDate->format('d.m.Y') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="doc-title">Финансовый отчет по эффективности</div>
    <div class="doc-meta">Данный документ сформирован автоматически и является официальным финансовым подтверждением за указанный период.</div>

    <table class="stats-grid">
        <tr>
            <td>
                <span class="stats-label">Валовая выручка</span>
                <span class="stats-value">{{ number_format($totalRevenue, 0, '.', ' ') }} ₽</span>
            </td>
            <td>
                <span class="stats-label">Операционные расходы</span>
                <span class="stats-value expenses">{{ number_format($expenses, 0, '.', ' ') }} ₽</span>
            </td>
            <td>
                <span class="stats-label">Чистая прибыль</span>
                <span class="stats-value profit">{{ number_format($profit, 0, '.', ' ') }} ₽</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Выручка по подразделениям (Салонам)</div>
    <div style="margin-bottom: 30px;">
        @foreach($salonRevenue as $salon)
            @php $percent = ($salon['revenue'] / $maxSalonRevenue) * 100; @endphp
            <div class="chart-row">
                <div class="chart-label">{{ $salon['name'] }} — {{ number_format($salon['revenue'], 0, '.', ' ') }} ₽</div>
                <div class="chart-bar-bg">
                    <div class="chart-bar-fill" style="width: {{ $percent }}%;"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="section-title">Эффективность специалистов</div>
    <div style="margin-bottom: 30px;">
        @foreach($masterPerformance as $master)
            @php 
                $revPercent = ($master['revenue'] / $maxMasterRevenue) * 100;
                $countPercent = ($master['count'] / $maxMasterCount) * 100;
            @endphp
            <div class="chart-row">
                <div class="chart-label">{{ $master['name'] }} ({{ $master['level'] }}) — {{ number_format($master['revenue'], 0, '.', ' ') }} ₽ / {{ $master['count'] }} зап.</div>
                <div class="chart-bar-bg" style="height: 6px; margin-bottom: 2px;">
                    <div class="chart-bar-fill" style="width: {{ $revPercent }}%;"></div>
                </div>
                <div class="chart-bar-bg" style="height: 4px;">
                    <div class="chart-bar-fill secondary" style="width: {{ $countPercent }}%;"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="section-title">Топ востребованных услуг ({{ $serviceSort === 'asc' ? 'Анти-топ' : 'Рейтинг' }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Наименование услуги</th>
                <th style="text-align: center;">Кол-во</th>
                <th style="text-align: right;">Выручка</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topServices as $service)
            <tr>
                <td style="font-weight: bold;">{{ $service['name'] }}</td>
                <td style="text-align: center;">{{ $service['count'] }}</td>
                <td style="text-align: right;">{{ number_format($service['revenue'], 0, '.', ' ') }} ₽</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-block">
        <table class="signature-table">
            <tr>
                <td class="signature-cell">
                    <div class="signature-line"></div>
                    <div class="signature-label">Генеральный директор</div>
                </td>
                <td class="signature-cell" style="text-align: right;">
                    <div class="stamp-box">
                        <div class="official-stamp">
                            ШОКОЛАД ЛАЙФ<br>
                            М.П.<br>
                            <span style="font-size: 6pt;">ОФИЦИАЛЬНЫЙ ДОКУМЕНТ</span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="signature-cell">
                    <div class="signature-line"></div>
                    <div class="signature-label">Главный бухгалтер</div>
                </td>
                <td class="signature-cell"></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Документ является интеллектуальной собственностью сети «Шоколад лайф» — {{ date('Y') }}<br>
        Сгенерировано в системе автоматизации бизнеса Choco Backend v2.5
    </div>
</body>
</html>
