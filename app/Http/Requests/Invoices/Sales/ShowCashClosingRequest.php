<?php

namespace App\Http\Requests\Invoices\Sales;

use App\Rules\Products\StartedInventory;
use Illuminate\Foundation\Http\FormRequest;

class ShowCashClosingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $authorized = true;
        if(!is_null($this->get('user'))){
            if(!auth()->user()->can('users')){
                $authorized = false;
            }
        }
        return $authorized;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $today = date('Y-m-d');
        return [
            'warehouse' => 'nullable|integer|exists:warehouses,id',
            'user' => 'nullable|integer|exists:users,id',
            'product' => ['nullable', 'integer', 'exists:products,id', new StartedInventory],
            'date_from' => "required|date_format:Y-m-d|before_or_equal:date_to",
            'date_to' => "required|date_format:Y-m-d|before_or_equal:$today",
            'page' => 'integer|min:1'
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse' => 'bodega',
            'user' => 'usuario',
            'product' => 'producto',
            'date_from' => 'fecha incial',
            'date_to' => 'fecha final',
            'page' => 'página'
        ];
    }
}
