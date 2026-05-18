@extends('layouts.app')

@section('title', 'Личный кабинет — Шоколад')

@section('styles')
<style>
    .page-header {
        padding: 150px 0 60px;
        background-color: var(--chocolate);
        color: var(--white);
        text-align: center;
    }

    .profile-container {
        max-width: 900px;
        margin: 50px auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .auth-card {
        background: var(--white);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .auth-card h2 {
        font-family: 'Playfair Display', serif;
        color: var(--chocolate);
        margin-bottom: 25px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: inherit;
    }

    .btn-auth {
        width: 100%;
        padding: 15px;
        background-color: var(--chocolate);
        color: var(--white);
        border: none;
        border-radius: 5px;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-auth:hover {
        background-color: var(--gold);
        color: var(--chocolate);
    }

    .btn-telegram {
        background-color: #0088cc;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-transform: none;
    }

    .btn-telegram:hover {
        background-color: #0077b5;
    }

    .telegram-icon {
        font-size: 24px;
    }

    .divider {
        text-align: center;
        margin: 20px 0;
        color: #999;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #ddd;
    }

    .divider span {
        background: var(--white);
        padding: 0 15px;
        position: relative;
        z-index: 1;
    }

    .dashboard-container {
        max-width: 1000px;
        margin: 50px auto;
        background: var(--white);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #f5f5f5;
        padding-bottom: 20px;
    }

    .booking-history {
        margin-top: 30px;
    }

    .booking-item {
        display: flex;
        justify-content: space-between;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .booking-info h4 {
        color: var(--chocolate);
        margin-bottom: 5px;
    }

    .booking-status {
        font-weight: 600;
        color: var(--gold);
    }

    .dashboard-grid-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 40px;
    }

    @media (max-width: 768px) {
        .profile-container {
            grid-template-columns: 1fr;
            padding: 0 15px;
        }

        .page-header {
            padding: 100px 15px 40px;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .dashboard-container {
            margin: 20px 10px;
            padding: 20px 15px;
        }

        .dashboard-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .dashboard-header h2 {
            font-size: 20px;
        }

        .booking-item {
            flex-direction: column;
            gap: 10px;
        }

        .booking-status {
            text-align: left !important;
        }

        .auth-card {
            padding: 25px 20px;
        }

        .dashboard-grid-layout {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@if(auth('client')->check())
    <script>
        window.location.href = '{{ route('client.dashboard') }}';
    </script>
@else
<div class="page-header">
    <div class="container">
        <h1>Личный кабинет</h1>
    </div>
</div>

<div class="container">
        <div style="max-width: 550px; margin: 50px auto;">
            <div class="auth-card">
                <h2>Вход в кабинет</h2>
                <p style="margin-bottom: 30px; color: var(--text-light);">Для доступа к личному кабинету, пожалуйста, авторизуйтесь через Telegram.</p>

                <!-- Display errors if any -->
                @if($errors->any())
                    <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- Telegram Login Widget -->
                <div style="margin-bottom: 20px;">
                    <p style="margin-bottom: 20px; font-size: 14px; color: var(--text-light); text-align: center;">
                        Нажмите кнопку ниже для быстрого входа через Telegram
                    </p>
                    <div style="display: flex; justify-content: center;">
                        <script async src="https://telegram.org/js/telegram-widget.js?22" 
                                data-telegram-login="{{ config('services.telegram.bot_username') }}"
                                data-size="large"
                                data-auth-url="{{ route('auth.telegram') }}"
                                data-request-access="write"
                                data-radius="5"></script>
                    </div>
                </div>

                @if(request('from') === 'booking')
                    <p style="margin-top: 20px; padding: 12px; background: #e3f2fd; border-radius: 8px; font-size: 14px; color: #1565c0;">
                        После входа вы сможете завершить онлайн-запись. <a href="{{ route('booking') }}" style="color: var(--chocolate); font-weight: 600;">Вернуться к записи</a>
                    </p>
                @endif
                <p style="margin-top: 30px; font-size: 14px; color: #888; text-align: center;">
                    Нажимая кнопку входа, вы соглашаетесь с правилами нашего сервиса.
                </p>
            </div>
        </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    async function cancelBooking(id) {
        if (!confirm('Вы уверены, что хотите отменить запись?')) return;
        
        try {
            const response = await fetch(`{{ url('/booking') }}/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                location.reload();
            } else {
                alert('Ошибка при отмене');
            }
        } catch (e) { alert('Ошибка сети'); }
    }

</script>
@endsection