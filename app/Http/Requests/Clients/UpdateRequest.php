<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $client = $this->route('client');
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('clients', 'name')->ignore($client->id)
            ],
            'phone' => 'nullable|string|min:10|max:20',
            'email' => 'nullable|string|min:3|max:400',
            'ruc' => 'nullable|string|min:10|max:20',
            'address' => 'nullable|string|min:2|max:255',
            'identity_card' => 'nullable|string|min:10|max:20',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'phone' => 'número teléfonico',
            'email' => 'correo electrónico',
            'ruc' => 'RUC',
            'address' => 'dirección',
            'identity_card' => 'número de cédula'
        ];
    }
}
