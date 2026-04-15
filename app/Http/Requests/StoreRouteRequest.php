<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'origin'      => ['required', 'string', 'max:100'],
            'destination' => ['required', 'string', 'max:100'],
            'distance_km' => ['nullable', 'numeric', 'min:0.1'],
        ];
    }
}