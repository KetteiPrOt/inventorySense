<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class ArrayDefaultKeys implements ValidationRule, ValidatorAwareRule
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
            $arrayKeys = array_keys($value);
            for($i = 0; $i < count($value); $i++){
                if(
                    $arrayKeys[$i] !== $i
                ){
                    $fail('Los datos son invalidos.');
                    break;
                }
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

    protected $validator;

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;
 
        return $this;
    }
}
