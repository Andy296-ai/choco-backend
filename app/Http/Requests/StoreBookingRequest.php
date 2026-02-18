<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Any authenticated user can book
    }

    public function rules(): array
    {
        return [
            'salon_id' => 'required|exists:salons,id',
            'service_id' => 'required|exists:services,id',
            'specialist_id' => 'required', // can be 'any'
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
        ];
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
