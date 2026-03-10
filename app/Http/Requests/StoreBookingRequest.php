<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Только авторизованные клиенты могут создавать бронирования
        return \Illuminate\Support\Facades\Auth::guard('client')->check();
    }

    protected function failedAuthorization()
    {
        // Важно: FormRequest отрабатывает до контроллера.
        // Для фронта (fetch) возвращаем 401 + флаг, чтобы показать модалку входа через Telegram.
        throw new HttpResponseException(
            response()->json([
                'error' => 'Для онлайн-записи необходимо войти через Telegram.',
                'require_auth' => true,
            ], 401)
        );
    }

    public function rules(): array
    {
        return [
            'salon_id' => 'required|exists:salons,id',
            'service_id' => 'required|exists:services,id',
            'specialist_id' => 'required', // can be 'any'
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'time' => [
                'required',
                'string',
                'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', // Формат HH:MM
            ],
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $date = $this->input('date');
            $time = $this->input('time');
            
            if ($date && $time) {
                try {
                    $dateTime = \Carbon\Carbon::parse($date . ' ' . $time);
                    
                    // Проверяем, что время не в прошлом
                    if ($dateTime->isPast()) {
                        $validator->errors()->add('time', 'Нельзя записаться на прошедшее время.');
                    }
                    
                    // Проверяем, что время в рабочее время (например, с 9 до 20)
                    $hour = (int) $dateTime->format('H');
                    if ($hour < 9 || $hour >= 20) {
                        $validator->errors()->add('time', 'Запись возможна только с 9:00 до 20:00.');
                    }
                } catch (\Exception $e) {
                    $validator->errors()->add('time', 'Неверный формат времени.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'salon_id.required' => 'Выберите салон',
            'service_id.required' => 'Выберите услугу',
            'specialist_id.required' => 'Выберите специалиста',
            'date.required' => 'Выберите дату',
            'date.after_or_equal' => 'Нельзя записаться на прошедшую дату',
            'time.required' => 'Выберите время',
        ];
    }
}
