@extends('layouts.director')

@section('title', 'База данных — Шоколад')

@section('styles')
<style>
    .tables-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 10px;
    }

    .table-card {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 18px;
        background: #fff;
        text-decoration: none;
        display: block;
        transition: box-shadow 0.2s, border-color 0.2s, transform 0.2s;
    }

    .table-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: var(--gold);
        transform: translateY(-3px);
    }

    .table-name {
        font-weight: 600;
        color: var(--chocolate);
        margin-bottom: 5px;
        font-size: 14px;
    }

    .table-desc { font-size: 12px; color: #888; }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 11px;
        background: #f5f5f5;
        color: #777;
        margin-top: 8px;
    }
</style>
@endsection

@section('content')
    <div class="header">
        <div>
            <h1 style="margin:0; font-family:'Playfair Display',serif; color:var(--chocolate);">База данных</h1>
            <p style="color:#777; margin:4px 0 0; font-size:13px;">Просмотр и управление таблицами проекта</p>
        </div>
    </div>

    <div class="content-card">
        @if(count($tableNames) > 0)
            <div class="tables-grid">
                @foreach($tableNames as $tableName)
                    <a href="{{ route('director.db.table', $tableName) }}" class="table-card">
                        <div class="table-name">{{ $tableName }}</div>
                        <div class="table-desc">Просмотр, создание, редактирование</div>
                        <span class="badge">Таблица</span>
                    </a>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:40px; color:#888;">
                <div style="font-size:40px; margin-bottom:12px; opacity:0.3;">🗄️</div>
                <p>Доступных таблиц не найдено</p>
            </div>
        @endif
    </div>
@endsection
