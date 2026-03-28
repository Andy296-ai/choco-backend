@extends('layouts.client')

@section('title', 'Мои записи — Шоколад')

@section('page-title', 'Мои записи')
@section('page-subtitle', 'История ваших записей в салоне')

@section('content')
    <div class="content-card">
        @if($bookings->count() > 0)
            <div class="bookings-list">
                @foreach($bookings as $booking)
                    <div class="booking-item">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="flex: 1;">
                                <h4>{{ $booking->service->name }}</h4>
                                <p><strong>Мастер:</strong> {{ $booking->specialist->name }}</p>
                                <p><strong>Салон:</strong> {{ $booking->salon->name }}</p>
                                <p><strong>Дата и время:</strong> {{ $booking->start_time->format('d.m.Y H:i') }}</p>
                                <p><strong>Длительность:</strong> {{ $booking->service->duration_minutes }} минут</p>
                            </div>
                            <div style="text-align: right; min-width: 120px;">
                                <span class="status-badge" style="
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
                                        @method('PATCH')
                                        <button type="submit" 
                                                style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-size: 12px; width: 100%;"
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
            <div style="text-align: center; padding: 60px 20px; color: var(--text-light);">
                <div style="font-size: 48px; margin-bottom: 20px;">📅</div>
                <h3 style="color: var(--chocolate); margin-bottom: 15px;">У вас пока нет записей</h3>
                <p style="margin-bottom: 30px;">Запишитесь на услугу в нашем салоне красоты</p>
                <a href="{{ route('booking') }}" style="background: var(--gold); color: var(--chocolate); text-decoration: none; padding: 15px 30px; border-radius: 8px; font-weight: 600; display: inline-block; transition: all 0.3s;">
                    Записаться на услугу
                </a>
            </div>
        @endif
    </div>
@endsection
