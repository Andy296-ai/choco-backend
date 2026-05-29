@extends('layouts.director')

@section('title', 'Новая запись — ' . $table)

@section('styles')
<style>
    .db-create-card { max-width: 720px; }

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
    .form-group textarea {
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

    .error-msg  { font-size: 12px; color: #f44336; margin-top: 4px; }
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
    .btn-gold { background: var(--gold); color: var(--chocolate); }
    .btn-grey { background: #eee; color: #555; }
</style>
@endsection

@section('content')
    <div class="header" style="margin-bottom:24px;">
        <h1 style="margin:0; font-family:'Playfair Display',serif; color:var(--chocolate); font-size:22px;">
            Новая запись в <span style="color:var(--gold);">{{ $table }}</span>
        </h1>
        <a href="{{ route('director.db.table', $table) }}" class="btn btn-grey">← К таблице</a>
    </div>

    <div class="content-card db-create-card">
        @if($errors->any())
            <div style="background:#ffebee; color:#c62828; padding:12px 16px; border-radius:6px; margin-bottom:16px; font-size:13px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('director.db.store', $table) }}">
            @csrf

            @foreach($formColumns as $column)
                @php
                    $inputType = 'text';
                    if (str_contains($column['type'], 'int'))      $inputType = 'number';
                    elseif (str_contains($column['type'], 'decimal') || str_contains($column['type'], 'float')) $inputType = 'number';
                    elseif (str_contains($column['type'], 'datetime') || str_contains($column['type'], 'timestamp')) $inputType = 'datetime-local';
                    elseif (str_contains($column['type'], 'date'))  $inputType = 'date';
                    elseif (str_contains($column['type'], 'time'))  $inputType = 'time';
                    elseif (str_contains($column['type'], 'text') || ($column['max_length'] && $column['max_length'] > 255)) $inputType = 'textarea';
                    elseif (str_contains($column['name'], 'email')) $inputType = 'email';
                    elseif (str_contains($column['name'], 'password')) $inputType = 'password';
                @endphp

                <div class="form-group">
                    <label>
                        {{ $column['name'] }}
                        @if(!$column['nullable'] && $column['default'] === null)
                            <span class="required-star">*</span>
                        @endif
                        <span class="type-hint">{{ $column['type'] }}</span>
                    </label>

                    @if($inputType === 'textarea')
                        <textarea name="{{ $column['name'] }}">{{ old($column['name'], $column['default']) }}</textarea>
                    @else
                        <input type="{{ $inputType }}"
                               name="{{ $column['name'] }}"
                               value="{{ old($column['name'], $column['default']) }}">
                    @endif

                    @error($column['name'])
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div style="display:flex; gap:10px; margin-top:24px;">
                <a href="{{ route('director.db.table', $table) }}" class="btn btn-grey">Отмена</a>
                <button type="submit" class="btn btn-gold">Создать запись</button>
            </div>
        </form>
    </div>
@endsection
