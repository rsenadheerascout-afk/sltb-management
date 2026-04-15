<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_no' => ['required', 'string', 'unique:buses,vehicle_no'],
            'depot_reg_no' => ['required', 'regex:/^YT\d{2,3}$/', 'unique:buses,depot_reg_no'],
            'brand' => ['required', 'string', 'max:100'],
            'seat_count' => ['required', 'integer', 'min:1', 'max:60'],
            'manufactured_year' => ['nullable', 'integer', 'min:1980', 'max:' . date('Y')],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'depot_reg_no.regex' => 'Depot registration number must be in format YTXXX (e.g. YT001, YT042, YT112).',
        ];
    }
}