<?php

namespace App\Rules\Products;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class SameSize implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    private string $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->validator->errors()->has($attribute)){
            if(
                !(count($this->data[$this->field]) === count($value))
            ){
                $fail('Los datos con invalidos.');
            }
        }
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
