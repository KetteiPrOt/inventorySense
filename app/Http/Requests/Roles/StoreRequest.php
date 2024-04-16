<?php

namespace App\Http\Requests\Roles;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:125|unique:roles,name',
            'products' => 'sometimes|accepted',
            'providers' => 'sometimes|accepted',
            'clients' => 'sometimes|accepted',
            'create-purchases' => 'sometimes|accepted',
            'kardex' => 'sometimes|accepted',
            'purchases-report' => 'sometimes|accepted',
            'create-sales' => 'sometimes|accepted',
            'cash-closing' => 'sometimes|accepted',
            'see-all-incomes' => 'sometimes|accepted|exclude_without:cash-closing',
            'sales-report' => 'sometimes|accepted',
            'see-all-sales' => 'sometimes|accepted|exclude_without:sales-report',
            'edit-all-sales' => 'sometimes|accepted|exclude_without:see-all-sales,sales-report',
            'inventory' => 'sometimes|accepted'
        ];
    }

    public function attributes(): array
    {
        return Permission::$directPermissionNames;
    }
}
