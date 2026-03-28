@extends('layouts.app')

@section('title', 'Мои записи — Шоколад')

@section('styles')
    @include('client.partials.area-styles')
@endsection

@section('content')
    <div class="page-header client-page-header">
        <div class="container">
            <h1>Мои записи</h1>
            <p class="client-page-subtitle">История ваших записей в салоне</p>
        </div>
    </div>

    <div class="container client-area-main">
        <div class="client-panel">
            @if($bookings->count() > 0)
                @foreach($bookings as $booking)
                    <div class="client-booking-row">
                        <div style="flex: 1; min-width: 0;">
                            <h4>{{ $booking->service->name }}</h4>
                            <p><strong>Мастер:</strong> {{ $booking->specialist->name }}</p>
                            <p><strong>Салон:</strong> {{ $booking->salon->name }}</p>
                            <p><strong>Дата и время:</strong> {{ $booking->start_time->format('d.m.Y H:i') }}</p>
                            <p><strong>Длительность:</strong> {{ $booking->service->duration_minutes }} минут</p>
                        </div>
                        <div class="client-booking-side">
                            <span class="client-badge"
                                @if($booking->status === 'pending') style="background: #ffc107; color: #856404;"
                                @elseif($booking->status === 'confirmed') style="background: #2e7d32; color: white;"
                                @elseif($booking->status === 'completed') style="background: #616161; color: white;"
                                @elseif($booking->status === 'cancelled') style="background: #c62828; color: white;"
                                @endif>
                                @if($booking->status === 'pending') Ожидает
                                @elseif($booking->status === 'confirmed') Подтверждено
                                @elseif($booking->status === 'completed') Завершено
                                @elseif($booking->status === 'cancelled') Отменено
                                @endif
                            </span>

                            @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                <form method="POST" action="{{ route('client.bookings.cancel', $booking->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="client-btn client-btn--danger"
                                        onclick="return confirm('Вы уверены, что хотите отменить запись?')">
                                        Отменить
                                    </button>
                                </form>
                            @endif

                            @if(in_array($booking->status, ['cancelled', 'completed']))
                                <form method="POST" action="{{ route('client.bookings.delete', $booking->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="client-btn client-btn--danger"
                                        onclick="return confirm('Вы уверены, что хотите удалить эту запись навсегда?')">
                                        Удалить
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($bookings->hasPages())
                    <div class="client-pagination">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <div class="client-empty">
                    <div class="client-empty-icon" aria-hidden="true">📅</div>
                    <h3>У вас пока нет записей</h3>
                    <p>Запишитесь на услугу в нашем салоне красоты</p>
                    <a href="{{ route('booking') }}" class="client-btn client-btn--gold">Записаться на услугу</a>
                </div>
            @endif
        </div>
    </div>
@endsection
