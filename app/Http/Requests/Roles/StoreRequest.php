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
        $rules['name'] = 'required|string|min:2|max:125|unique:roles,name';
        $permissions = Permission::$directPermissions;
        foreach($permissions as $permission){
            $rules[$permission] = 'sometimes|accepted';
        }
        return $rules;
    }

    public function attributes(): array
    {
        return Permission::$directPermissionNames;
    }
}
