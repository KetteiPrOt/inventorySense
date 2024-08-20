<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $report_type = match($this->get('report_type')){
            'all' => 'all',
            'under_min_stock' => 'under_min_stock',
            'not_stock' => 'not_stock',
            default => 'all'
        };
        $column = match($this->get('column')){
            'tag' => 'tag',
            'existences' => 'existences',
            'unitary_price' => 'unitary_price',
            'total_price' => 'total_price',
            default => 'tag'
        };
        $order = match($this->get('order')){
            'desc' => 'desc',
            'asc' => 'asc',
            default => 'asc'
        };
        $inputs = $this->all();
        $inputs['report_type'] = $report_type;
        $inputs['column'] = $column;
        $inputs['order'] = $order;
        $this->replace($inputs);
    }
}
