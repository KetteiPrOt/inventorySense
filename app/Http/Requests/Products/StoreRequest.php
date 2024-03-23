<?php

namespace App\Http\Requests\Products;

use App\Rules\Products\SameSize;
use App\Rules\Products\UniqueTag;
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
        return [
            'product_type' => 'required|integer|exists:product_types,id',
            'product_presentation' => 'required|integer|exists:product_presentations,id',
            'product_name' => ['required', 'string', 'min:2', 'max:255', new UniqueTag],
            'min_stock' => 'required|integer|min:1|max:65000',
            'sale_prices' => 'required|array|min:1',
            'sale_prices.*' => 'decimal:0,2|min:0.01|max:9999.99|distinct:strict',
            'units_numbers' => ['required', 'array', 'min:1', new SameSize('sale_prices')],
            'units_numbers.0' => 'max:1',
            'units_numbers.*' => 'integer|min:1|max:65000|distinct:strict'
        ];
    }

    public function attributes(): array
    {
        return [
            'product_type' => 'tipo de producto',
            'product_presentation' => 'presentación de producto',
            'product_name' => 'nombre de producto',
            'min_stock' => 'stock mínimo',
            'units_numbers' => 'números de unidades',
            'units_numbers.*' => 'número de unidades :position',
            'sale_prices' => 'precios de venta',
            'sale_prices.*' => 'precio de venta :position',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Transform the product name to uppercase
        if(is_string($this->product_name)){
            $this->merge([
                'product_name' => mb_strtoupper($this->product_name)
            ]);
        }
        // Parse the sale prices to float value
        if(is_array($this->sale_prices)){
            $salePricesFixed = [];
            foreach($this->sale_prices as $key => $saleprice){
                if(is_numeric($saleprice)){
                    $saleprice = floatval($saleprice);
                }
                $salePricesFixed[$key] = $saleprice;
            }
            $this->merge([
                'sale_prices' => $salePricesFixed
            ]);
        }
    }
}
