@extends('layouts.director')

@section('title', 'Редактирование #' . $id . ' — ' . $table)

@section('styles')
<style>
    .db-edit-card {
        max-width: 720px;
    }

    .db-edit-card h1 {
        font-family: 'Raleway', sans-serif;
        color: var(--chocolate);
        font-size: 22px;
        margin: 0 0 24px;
    }

    .form-group { margin-bottom: 16px; }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 5px;
    }

    .form-group label .type-hint {
        color: #bbb;
        font-weight: 400;
        text-transform: none;
        font-size: 11px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus { border-color: var(--gold); }

    .form-group textarea { resize: vertical; min-height: 80px; }

    .input-readonly {
        background: #f5f5f5;
        color: #888;
        cursor: not-allowed;
    }

    .error-msg { font-size: 12px; color: #f44336; margin-top: 4px; }

    .required-star { color: #f44336; }

    .btn {
        display: inline-block;
        padding: 10px 22px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    .btn:hover { opacity: 0.85; }
    .btn-gold  { background: var(--gold); color: var(--chocolate); }
    .btn-grey  { background: #eee; color: #555; }
</style>
@endsection

@section('content')
    <div class="header" style="margin-bottom:24px;">
        <div>
            <h1 style="margin:0; font-family:'Raleway',serif; color:var(--chocolate); font-size:22px;">
                Редактировать запись
                <span style="color:var(--gold);">#{{ $id }}</span>
                в&nbsp;<span style="color:var(--gold);">{{ $table }}</span>
            </h1>
        </div>
        <a href="{{ route('director.db.table', $table) }}" class="btn btn-grey">← К таблице</a>
    </div>

    <div class="content-card db-edit-card">
        @if($errors->any())
            <div style="background:#ffebee; color:#c62828; padding:12px 16px; border-radius:6px; margin-bottom:16px; font-size:13px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('director.db.update', [$table, $id]) }}">
            @csrf
            @method('PUT')

            @foreach($formColumns as $column)
                @php
                    $inputType = 'text';
                    $val = old($column['name'], $row->{$column['name']});

                    if (str_contains($column['type'], 'int'))      $inputType = 'number';
                    elseif (str_contains($column['type'], 'decimal') || str_contains($column['type'], 'float')) $inputType = 'number';
                    elseif (str_contains($column['type'], 'datetime') || str_contains($column['type'], 'timestamp')) {
                        $inputType = 'datetime-local';
                        if ($val && $val !== '0000-00-00 00:00:00') $val = date('Y-m-d\TH:i', strtotime($val));
                    }
                    elseif (str_contains($column['type'], 'date')) {
                        $inputType = 'date';
                        if ($val && $val !== '0000-00-00') $val = date('Y-m-d', strtotime($val));
                    }
                    elseif (str_contains($column['type'], 'time'))  $inputType = 'time';
                    elseif (str_contains($column['type'], 'text') || ($column['max_length'] && $column['max_length'] > 255)) $inputType = 'textarea';
                    elseif (str_contains($column['name'], 'email')) $inputType = 'email';
                    elseif (str_contains($column['name'], 'password')) $inputType = 'password';
                @endphp

                <div class="form-group">
                    <label>
                        {{ $column['name'] }}
                        @if($column['name'] === $primaryKey)
                            <span class="type-hint">(primary key)</span>
                        @elseif(!$column['nullable'] && $column['default'] === null)
                            <span class="required-star">*</span>
                        @endif
                        <span class="type-hint">{{ $column['type'] }}</span>
                    </label>

                    @if($column['name'] === $primaryKey)
                        <input type="text" value="{{ $val }}" class="input-readonly" readonly disabled>
                        <input type="hidden" name="{{ $column['name'] }}" value="{{ $val }}">
                    @elseif($inputType === 'textarea')
                        <textarea name="{{ $column['name'] }}">{{ $val }}</textarea>
                    @else
                        <input type="{{ $inputType }}" name="{{ $column['name'] }}" value="{{ $val }}">
                    @endif

                    @error($column['name'])
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div style="display:flex; gap:10px; margin-top:24px;">
                <a href="{{ route('director.db.table', $table) }}" class="btn btn-grey">Отмена</a>
                <button type="submit" class="btn btn-gold">Сохранить изменения</button>
            </div>
        </form>
    </div>
@endsection
