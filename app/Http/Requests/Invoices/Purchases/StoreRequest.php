<?php

namespace App\Http\Requests\Invoices\Purchases;

use App\Rules\ArrayDefaultKeys;
use App\Rules\ArraySameSize;
use App\Rules\Invoices\Purchases\MovementTypes;
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
        $tomorrow = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $nextMonth = date('Y-m-d', mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")));
        return [
            'warehouse' => 'required|integer|exists:warehouses,id',
            'number' => 'nullable|string|min:17|max:17',
            'provider' => 'nullable|integer|exists:providers,id',
            'paid' => 'sometimes|accepted',
            'due_payment_date' => 
                "required_without:paid|exclude_with:paid|date_format:Y-m-d|after_or_equal:$tomorrow|before_or_equal:$nextMonth",
            'comment' => 'nullable|string|min:2|max:1000',
            'products' => ['required', 'array', 'min:1', new ArrayDefaultKeys],
            'products.*' => 'integer|exists:products,id',
            'movement_types' => [
                'required', 'array', 'min:1',
                new ArraySameSize('products'), new ArrayDefaultKeys, new MovementTypes
            ],
            'movement_types.*' => ['integer', 'exists:movement_types,id'],
            'amounts' => [
                'required', 'array', 'min:1',
                new ArraySameSize('movement_types'), new ArrayDefaultKeys
            ],
            'amounts.*' => 'integer|min:1|max:65000',
            'unitary_purchase_prices' => [
                'required', 'array', 'min:1',
                new ArraySameSize('amounts'), new ArrayDefaultKeys
            ],
            'unitary_purchase_prices.*' => 'decimal:0,2|min:0.01|max:999999.99'
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse' => 'bodega',
            'number' => 'número de factura',
            'paid' => 'factura pagada',
            'due_payment_date' => 'fecha de vencimiento',
            'comment' => 'comentario',
            'products' => 'productos',
            'products.*' => 'producto #:position',
            'movement_types' => 'tipos de movimiento',
            'movement_types.*' => 'tipo de movimiento #:position',
            'amounts' => 'cantidades',
            'amounts.*' => 'cantidad #:position',
            'unitary_purchase_prices' => 'precios unitarios',
            'unitary_purchase_prices.*' => 'precio unitario #:position'
        ];
    }
}
