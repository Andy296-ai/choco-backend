@extends('layouts.app')

@section('title', 'Мои записи — Шоколад')

@section('content')
    <div class="container">
        <div class="header">
            <h1>Мои записи</h1>
        </div>

        <div class="content-card">
            @if($bookings->count() > 0)
                <div class="bookings-list">
                    @foreach($bookings as $booking)
                        <div class="booking-item" style="padding: 15px; border-bottom: 1px solid #f5f5f5; margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h4 style="margin: 0; color: var(--chocolate);">{{ $booking->service->name }}</h4>
                                    <p style="margin: 5px 0; color: #666; font-size: 14px;">
                                        <strong>Мастер:</strong> {{ $booking->specialist->name }}<br>
                                        <strong>Салон:</strong> {{ $booking->salon->name }}<br>
                                        <strong>Дата и время:</strong> {{ $booking->start_time->format('d.m.Y H:i') }}<br>
                                        <strong>Длительность:</strong> {{ $booking->service->duration_minutes }} минут
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <span class="status-badge" style="
                                        padding: 5px 10px; 
                                        border-radius: 20px; 
                                        font-size: 12px; 
                                        font-weight: 600;
                                        {{ $booking->status === 'pending' ? 'background: #ffc107; color: #856404;' : '' }}
                                        {{ $booking->status === 'confirmed' ? 'background: #28a745; color: white;' : '' }}
                                        {{ $booking->status === 'completed' ? 'background: #6c757d; color: white;' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'background: #dc3545; color: white;' : '' }}
                                    ">
                                        {{ $booking->status === 'pending' ? 'Ожидает' : '' }}
                                        {{ $booking->status === 'confirmed' ? 'Подтверждено' : '' }}
                                        {{ $booking->status === 'completed' ? 'Завершено' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'Отменено' : '' }}
                                    </span>
                                    
                                    @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                        <form method="POST" action="{{ route('client.bookings.cancel', $booking->id) }}" style="margin-top: 10px;">
                                            @csrf
                                            <button type="submit" 
                                                    style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 12px;"
                                                    onclick="return confirm('Вы уверены, что хотите отменить запись?')">
                                                Отменить
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Пагинация -->
                @if($bookings->hasPages())
                    <div style="margin-top: 30px; text-align: center;">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>У вас пока нет записей.</p>
                    <a href="{{ route('booking') }}" style="background: var(--gold); color: var(--chocolate); text-decoration: none; padding: 12px 24px; border-radius: 5px; font-weight: 600; display: inline-block; margin-top: 20px;">
                        Записаться на услугу
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
