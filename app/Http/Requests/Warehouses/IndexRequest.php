<?php

namespace App\Http\Requests\Warehouses;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|min:2|max:255',
            'column' => 'nullable|string|min:2|max:255',
            'order' => 'nullable|string|min:3|max:4'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $column = match($this->get('column') ?? null){
            default => 'name'
        };
        $order = match($this->get('order') ?? null){
            'desc' => 'desc', default => 'asc'
        };
        $inputs = $this->all();
        $inputs['column'] = $column;
        $inputs['order'] = $order;
        $this->replace($inputs);
    }
}
