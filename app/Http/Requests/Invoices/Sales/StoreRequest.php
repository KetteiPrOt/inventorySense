<?php

namespace App\Http\Requests\Invoices\Sales;

use App\Rules\ArrayDefaultKeys;
use App\Rules\ArraySameSize;
use App\Rules\Invoices\Sales\ValidMovementsData;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $threeDaysAgo = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 3, date("Y")));
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $nextMonth = date('Y-m-d', mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")));
        return [
            'date' => "required|date_format:Y-m-d|after_or_equal:$threeDaysAgo|before_or_equal:$today",
            'warehouse' => 'required|integer|exists:warehouses,id',
            'client' => 'nullable|integer|exists:clients,id',
            'paid' => 'sometimes|accepted',
            'due_payment_date' => "required_without:paid|exclude_with:paid|date_format:Y-m-d|after_or_equal:$tomorrow|before_or_equal:$nextMonth",
            'comment' => 'nullable|string|min:2|max:1000',
            'products' => ['required', 'array', 'min:1', new ArrayDefaultKeys],
            'products.*' => 'integer|exists:products,id',
            'movement_types' => [
                'required', 'array', 'min:1',
                new ArraySameSize('products'), new ArrayDefaultKeys
            ],
            'movement_types.*' => ['integer', 'exists:movement_types,id'],
            'amounts' => [
                'required', 'array', 'min:1',
                new ArraySameSize('movement_types'), new ArrayDefaultKeys
            ],
            'amounts.*' => 'integer|min:1|max:65000',
            'unitary_sale_prices' => [
                'required', 'array', 'min:1',
                new ArraySameSize('amounts'), new ArrayDefaultKeys, new ValidMovementsData
            ],
            'unitary_sale_prices.*' => 'integer|exists:product_sale_prices,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'date' => 'fecha',
            'warehouse' => 'bodega',
            'client' => 'cliente',
            'paid' => 'factura pagada',
            'due_payment_date' => 'fecha de vencimiento',
            'comment' => 'comentario',
            'products' => 'productos',
            'products.*' => 'producto #:position',
            'movement_types' => 'tipos de movimiento',
            'movement_types.*' => 'tipo de movimiento #:position',
            'amounts' => 'cantidades',
            'amounts.*' => 'cantidad #:position',
            'unitary_sale_prices' => 'precios unitarios',
            'unitary_sale_prices.*' => 'precio unitario #:position'
        ];
    }
}
