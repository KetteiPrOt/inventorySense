<?php

namespace App\Rules\Invoices\Purchases;

use App\Models\Invoices\Movements\Type as MovementType;
use App\Models\Products\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class MovementTypes implements ValidationRule, DataAwareRule, ValidatorAwareRule
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
                $movementType = MovementType::find($value[$i]);
                if(!$this->validExpenseType($movementType)){
                    $fail('El tipo de movimiento es inválido.');
                    break;
                }
                $product = Product::find($this->data['products'][$i]);
                if(!$this->validProductInventory($product, $movementType)){
                    $fail('El producto no tiene el inventario iniciado.');
                    break;
                }
            }
        }
    }

    private function validExpenseType(?MovementType $type): bool
    {
        return !is_null($type) 
            && ($type?->category === 'e')
            && ($type?->name !== MovementType::$warehouseChangeExpenseName);
    }

    private function validProductInventory(Product $product, MovementType $movementType): bool
    {
        $initialInventory = MovementType::initialInventory();
        if($product->started_inventory){
            $valid = $movementType->id !== $initialInventory->id;
        } else {
            $valid = $movementType->id === $initialInventory->id;
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
