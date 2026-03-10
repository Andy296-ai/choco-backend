<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Редактирование записи в {{ $table }} — Управление БД</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
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

        .nav-item a:hover,
        .nav-item a.active {
            background-color: rgba(255, 255, 255, 0.1);
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
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: var(--chocolate);
            font-family: 'Playfair Display', serif;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gold);
            color: var(--chocolate);
        }

        .btn-secondary {
            background: #eee;
            color: #555;
        }

        .card {
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 13px;
            margin-bottom: 5px;
            color: #555;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 13px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .required {
            color: #f44336;
        }

        .error {
            font-size: 12px;
            color: #f44336;
            margin-top: 3px;
        }

        .readonly {
            background: #f5f5f5;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ШОКОЛАД</h2>
        <ul class="nav-menu">
            <li class="nav-item"><a href="{{ route('director.dashboard') }}">Дашборд</a></li>
            <li class="nav-item"><a href="{{ route('director.employees') }}">Сотрудники</a></li>
            <li class="nav-item"><a href="{{ route('director.finance') }}">Финансы</a></li>
            <li class="nav-item"><a href="{{ route('director.settings') }}">Настройки</a></li>
            <li class="nav-item"><a href="{{ route('director.db.index') }}" class="active">База данных</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Редактирование записи #{{ $id }} в {{ $table }}</h1>
            </div>
            <div>
                <a href="{{ route('director.db.table', $table) }}" class="btn btn-secondary">← К таблице</a>
            </div>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('director.db.update', [$table, $id]) }}">
                @csrf
                @method('PUT')

                @foreach($formColumns as $column)
                    @php
                        $inputType = 'text';
                        $value = old($column['name'], $row->{$column['name']});

                        if (strpos($column['type'], 'int') !== false) {
                            $inputType = 'number';
                        } elseif (strpos($column['type'], 'decimal') !== false ||
                            strpos($column['type'], 'float') !== false ||
                            strpos($column['type'], 'double') !== false
                        ) {
                            $inputType = 'number';
                        } elseif (strpos($column['type'], 'date') !== false && strpos($column['type'], 'time') === false) {
                            $inputType = 'date';
                            if ($value && $value !== '0000-00-00') {
                                $value = date('Y-m-d', strtotime($value));
                            }
                        } elseif (strpos($column['type'], 'datetime') !== false || strpos($column['type'], 'timestamp') !== false) {
                            $inputType = 'datetime-local';
                            if ($value && $value !== '0000-00-00 00:00:00') {
                                $value = date('Y-m-d\TH:i', strtotime($value));
                            }
                        } elseif (strpos($column['type'], 'time') !== false) {
                            $inputType = 'time';
                        } elseif (strpos($column['type'], 'text') !== false || ($column['max_length'] && $column['max_length'] > 255)) {
                            $inputType = 'textarea';
                        } elseif ($column['name'] === 'email' || strpos($column['name'], 'email') !== false) {
                            $inputType = 'email';
                        } elseif ($column['name'] === 'password' || strpos($column['name'], 'password') !== false) {
                            $inputType = 'password';
                        }
                    @endphp
                    <div class="form-group">
                        <label>
                            {{ $column['name'] }}
                            @if($column['name'] === $primaryKey)
                                <span style="font-size:11px; color:#999;">(primary key)</span>
                            @elseif(!$column['nullable'] && $column['default'] === null)
                                <span class="required">*</span>
                            @endif
                            <span style="font-size:11px; color:#999;">({{ $column['type'] }})</span>
                        </label>
                        @if($column['name'] === $primaryKey)
                            <input
                                type="text"
                                value="{{ $value }}"
                                class="readonly"
                                readonly
                                disabled
                            >
                            <input type="hidden" name="{{ $column['name'] }}" value="{{ $value }}">
                        @elseif($inputType === 'textarea')
                            <textarea
                                name="{{ $column['name'] }}"
                            >{{ $value }}</textarea>
                        @else
                            <input
                                type="{{ $inputType }}"
                                name="{{ $column['name'] }}"
                                value="{{ $value }}"
                            >
                        @endif
                        @error($column['name'])
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <div style="margin-top:20px; display:flex; gap:10px;">
                    <a href="{{ route('director.db.table', $table) }}" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

