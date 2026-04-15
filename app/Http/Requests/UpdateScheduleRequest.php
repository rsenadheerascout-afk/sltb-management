<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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
            'arrival_time'   => ['required', 'date_format:H:i'],
            'schedule_date'  => ['required', 'date'],
            'fare'           => ['required', 'numeric', 'min:1'],
        ];
    }
}