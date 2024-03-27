<?php

namespace App\Http\Requests\Providers;

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
        $provider = $this->route('provider');
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('providers', 'name')->ignore($provider->id)
            ],
            'phone' => 'nullable|string|min:10|max:20',
            'email' => 'nullable|string|min:3|max:400',
            'ruc' => 'nullable|string|min:10|max:20',
            'address' => 'nullable|string|min:2|max:255',
            'social_reason' => 'nullable|string|min:2|max:255',
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
            'social_reason' => 'razón social'
        ];
    }
}
