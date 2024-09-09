<?php

namespace App\Http\Requests\Inventory\WarehouseChange;

use App\Models\Products\Product;
use App\Rules\ArrayDefaultKeys;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ChangeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from_warehouse' => 'required|integer|exists:warehouses,id',
            'to_warehouse' => 'required|integer|exists:warehouses,id|different:from_warehouse',
            'products' => [
                'required',
                'array',
                'min:1',
                'max:50',
                new ArrayDefaultKeys()
            ],
            'products.*' => 'integer|exists:products,id',
            'amounts' => [
                'required',
                'array',
                'min:1',
                'max:50',
                new ArrayDefaultKeys(),
            ],
            'amounts.*' => 'integer|min:1|max:4000000000',
        ];
    }

    public function attributes()
    {
        return [
            'from_warehouse' => 'bodega (desde)',
            'to_warehouse' => 'bodega (hacia)',
            'products' => 'productos',
            'amounts' => 'cantidades',
            'products.*' => 'producto #:position',
            'amounts.*' => 'cantidad #:position'
        ];
    }

    public function messages()
    {
        return [
            'to_warehouse.different' => 'Las bodegas seleccionadas deben ser diferentes.'
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator){
                if(count($validator->errors()->all()) > 0) return;
                if($this->insufficientAmounts()){
                    $validator->errors()->add(
                        'amounts',
                        'Las existencias no son suficientes para realizar el cambio.'
                    );
                }
            }
        ];
    }

    /**
     * Check if the products have sufficient existences in the given warehouse
     */
    private function insufficientAmounts(): bool
    {
        $from_warehouse = $this->get('from_warehouse');
        $amounts = $this->get('amounts');
        foreach($this->get('products') as $key => $id){
            $product = Product::find($id);
            $product->loadWarehouseExistences($from_warehouse);
            if($product->warehouse_existences < $amounts[$key]){
                return true;
            }
        }
        return false;
    }
}
