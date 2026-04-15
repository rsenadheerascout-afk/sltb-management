<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public — no auth required
    }

    public function rules(): array
    {
        return [
            'schedule_id'      => ['required', 'exists:schedules,id'],
            'seat_ids'         => ['required', 'array', 'min:1', 'max:6'],
            'seat_ids.*'       => ['exists:seats,id'],
            'passenger_name'   => ['required', 'string', 'max:255'],
            'passenger_email'  => ['required', 'email', 'max:255'],
            'passenger_phone'  => ['required', 'string', 'max:20'],
            'passenger_nic'    => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'seat_ids.required' => 'Please select at least one seat.',
            'seat_ids.max'      => 'You can book a maximum of 6 seats at once.',
        ];
    }
}