<?php

namespace App\Http\Requests\Inventory\WarehouseChange;

use Illuminate\Foundation\Http\FormRequest;

class SelectProductsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_warehouse' => 'required|integer|exists:warehouses,id',
            'to_warehouse' => 'required|integer|exists:warehouses,id|different:from_warehouse'
        ];
    }

    public function attributes()
    {
        return [
            'from_warehouse' => 'bodega (desde)',
            'to_warehouse' => 'bodega (hacia)'
        ];
    }

    public function messages()
    {
        return [
            'to_warehouse.different' => 'Las bodegas seleccionadas deben ser diferentes.'
        ];
    }
}
