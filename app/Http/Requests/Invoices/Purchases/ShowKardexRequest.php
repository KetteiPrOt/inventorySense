<?php

namespace App\Http\Requests\Invoices\Purchases;

use App\Rules\Products\StartedInventory;
use Illuminate\Foundation\Http\FormRequest;

class ShowKardexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $today = date('Y-m-d');
        return [
            'product' => ['required', 'integer', 'exists:products,id', new StartedInventory],
            'date_from' => "required|date_format:Y-m-d|before_or_equal:date_to",
            'date_to' => "required|date_format:Y-m-d|before_or_equal:$today"
        ];
    }

    public function attributes(): array
    {
        return [
            'product' => 'producto',
            'date_from' => 'fecha incial',
            'date_to' => 'fecha final'
        ];
    }
}
