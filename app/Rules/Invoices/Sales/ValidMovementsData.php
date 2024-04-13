<?php

namespace App\Rules\Invoices\Sales;

use App\Models\Invoices\Movements\Type;
use App\Models\Products\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class ValidMovementsData implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    private bool $stopWithFailures;

    public function __construct(bool $stopWithFailures = true)
    {
        $this->stopWithFailures = $stopWithFailures;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->resolveIfValidate()){
            for($i = 0; $i < count($value); $i++){
                $product = Product::with(['latestBalance', 'salePrices'])->find($this->data['products'][$i]);
                $movementType = Type::find($this->data['movement_types'][$i]);
                $amount = $this->data['amounts'][$i];
                $salePriceId = $value[$i];
                if(!$this->validSalePrice($salePriceId, $product, intval($amount))){
                    $fail('El precio de venta #' . ($i + 1) . ' es inválido.');
                    break;
                }
                if(!$product->started_inventory){
                    $fail('El producto #' . ($i + 1) . ' no tiene el inventario iniciado.');
                    break;
                }
                $product->loadWarehouseExistences($this->data['warehouse']);
                if(intval($amount) > $product->warehouse_existences){
                    $fail('La cantidad #' . ($i + 1) . ' es mayor a la disponible.');
                    break;
                }
                if($movementType->category !== 'i'){
                    $fail('El tipo de movimiento #' . ($i + 1) . ' es inválido.');
                    break;
                }
            }
        }
    }

    private function validSalePrice(mixed $salePriceId, Product $product, int $amount): bool
    {
        $valid = false;
        foreach($product->salePrices as $salePrice){
            if(
                $salePrice->id == $salePriceId
                && $amount >= $salePrice->units_number
            ){
                $valid = true;
                break;
            }
        }
        return $valid;
    }

    private function resolveIfValidate(): bool
    {
        $validate = true;
        if($this->stopWithFailures){
            if($this->validator->errors()->isNotEmpty()){
                $validate = false;
            }
        }
        return $validate;
    }

    protected $data = [];

    protected $validator;

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;
 
        return $this;
    }
}
