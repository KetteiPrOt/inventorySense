<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        return [
            'current_password' => 'required|string|min:2|max:255|current_password',
            'name' => 'required|string|min:2|max:255',
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'password' => ['nullable', 'string', 'max:255', Password::defaults(), 'confirmed'],
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
