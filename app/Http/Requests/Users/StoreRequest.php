<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', 'max:255', Rules\Password::defaults()],
            'address' => 'nullable|string|min:2|max:255',
            'identity_card' => 'nullable|string|min:10|max:20',
        ];
    }

    public function attributes(): array
    {
        return [
            'address' => 'dirección',
            'identity_card' => 'número de cédula'
        ];
    }
}
