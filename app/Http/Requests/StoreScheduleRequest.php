<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'route_id'       => ['required', 'exists:routes,id'],
            'bus_id'         => ['required', 'exists:buses,id'],
            'driver_id'      => ['nullable', 'exists:users,id'],
            'conductor_id'   => ['nullable', 'exists:users,id'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time'   => ['required', 'date_format:H:i', 'after:departure_time'],
            'schedule_date'  => ['required', 'date', 'after_or_equal:today'],
            'fare'           => ['required', 'numeric', 'min:1'],
        ];
    }
}