<?php

namespace App\Http\Requests\Roles;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $role = $this->route('role');
        $rules['name'] = [
            'required', 'string', 'min:2', 'max:125',
            Rule::unique('roles', 'name')->ignore($role->id)
        ];
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
