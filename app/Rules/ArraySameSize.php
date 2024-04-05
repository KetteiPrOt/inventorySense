<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class ArraySameSize implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    private bool $stopWithFailures;

    private string $anotherfield;

    public function __construct(string $anotherfield, bool $stopWithFailures = true)
    {
        $this->anotherfield = $anotherfield;
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
            if(
                count($this->data[$this->anotherfield]) !== count($value)
            ){
                $fail('Los datos son invalidos.');
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
