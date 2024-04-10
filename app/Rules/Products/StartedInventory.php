<?php

namespace App\Rules\Products;

use App\Models\Products\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class StartedInventory implements ValidationRule, DataAwareRule, ValidatorAwareRule
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
            $product = Product::find($value);
            if(!$product->started_inventory){
                $fail('El producto seleccionado no tiene el inventario iniciado.');
            }
        }
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
