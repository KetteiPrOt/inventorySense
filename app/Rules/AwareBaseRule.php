<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class AwareBaseRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    /**
     * Determine if stop the validation when there're errors
     */
    protected $stopWithErrors = true;

    protected $data = [];

    protected $validator;

    public function __construct(bool $stopWithErrors = true)
    {
        $this->stopWithErrors = $stopWithErrors;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->resolveIfValidate()){
            //
        }
    }

    protected function resolveIfValidate(): bool
    {
        $validate = true;
        if($this->stopWithErrors){
            if($this->validator->errors()->isNotEmpty()){
                $validate = false;
            }
        }
        return $validate;
    }

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
