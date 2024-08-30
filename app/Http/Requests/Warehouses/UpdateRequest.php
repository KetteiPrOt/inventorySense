<?php

namespace App\Http\Requests\Warehouses;

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
        $warehouse_id = $this->route('warehouse')->id;
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:255',
                Rule::unique('warehouses', 'name')->ignore($warehouse_id)
            ]
        ];
    }
}
