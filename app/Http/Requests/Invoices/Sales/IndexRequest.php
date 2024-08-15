<?php

namespace App\Http\Requests\Invoices\Sales;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $authorization = true;
        if(!is_null($this->get('user'))){
            $authUser = User::find(auth()->user()->id);
            if(!$authUser->can('see-all-sales')){
                $authorization = false;
            }
        }
        return $authorization;
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
            'date_from' => "required|date_format:Y-m-d|before_or_equal:date_to",
            'date_to' => "required|date_format:Y-m-d|before_or_equal:$today",
            'report_type' => 'required|string|min:3|max:13',
            'warehouse' => 'nullable|integer|exists:warehouses,id',
            'user' => 'nullable|integer|exists:users,id',
            'client' => 'nullable|integer|exists:clients,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'date_from' => 'fecha incial',
            'date_to' => 'fecha final',
            'report_type' => 'tipo de reporte',
            'warehouse' => 'bodega',
            'user' => 'usuario',
            'client' => 'cliente'
        ];
    }
}
