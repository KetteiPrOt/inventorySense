<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ShowIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'warehouse' => 'nullable|integer|exists:warehouses,id',
            'search_product' => 'nullable|string|min:2|max:255',
            'report_type' => 'required|string|min:3|max:15',
            'page' => 'integer|min:1',
            'column' => 'nullable|string|min:3|max:13',
            'order' => 'nullable|string|min:3|max:4'
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse' => 'bodega',
            'search_product' => 'buscar producto',
            'page' => 'página',
            'column' => 'columna',
            'order' => 'orden'
        ];
    }
}
