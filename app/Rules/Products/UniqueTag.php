<?php

namespace App\Rules\Products;

use App\Models\Products\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueTag implements ValidationRule, DataAwareRule
{
    private int $ignore;

    public function __construct(int $ignore = -1) {
        $this->ignore = $ignore;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $products = Product::with(['type', 'presentation'])->get();
        foreach($products as $product){
            if($product->id == $this->ignore){
                continue;
            }
            if(
                $product->type?->id == $this->data['product_type']
                && $product->presentation?->id == $this->data['product_presentation']
                && $product->name == $value
            ){
                $fail('El producto ya ha sido registrado.');
            }
        }
    }

    protected $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
