<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\StoreRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255',
            'column' => 'nullable|string|size:4',
            'order' => 'nullable|string|min:3|max:4'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('users.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query = Role::whereRaw("`name` LIKE ?", ["%$search%"]);
        }
        $column = match($validated['column'] ?? null){
            'name' => 'name', default => 'name'
        };
        $order = match($validated['order'] ?? null){
            'desc' => 'desc', 'asc' => 'asc', default => 'asc'
        };
        $roles = isset($validated['search'])
            ? $query->orderBy($column, $order)
            : Role::orderBy($column, $order);
        $roles = $roles->paginate(15)->withQueryString();
        foreach($roles as $key => $role){
            $role->n =
                ($key + 1) + ($roles->currentPage() - 1) * $roles->perPage();
        }
        return view('entities.roles.index', [
            'roles' => $roles,
            'filters' => [
                'column' => $column,
                'order' => $order
            ]
        ]);
    }

    public function create()
    {
        return view('entities.roles.create', [
            'translator' => Permission::translator()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $role = Role::create(['name' => $validated['name']]);
        foreach(Permission::$directPermissions as $permission){
            if(isset($validated[$permission])){
                $role->givePermissionTo($permission);
            }
        }
        return redirect()->route('roles.show', $role->id);
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('entities.roles.show', [
            'role' => $role,
            'translator' => Permission::translator(),
            'superAdmin' => Role::$superAdmin
        ]);
    }

    public function edit(Role $role)
    {
        if($role->name === Role::$superAdmin){
            return redirect()->route('roles.index');
        }
        $role->load(['permissions', 'users']);
        return view('entities.roles.edit', [
            'role' => $role,
            'translator' => Permission::translator()
        ]);
    }

    public function update(UpdateRequest $request, Role $role)
    {
        if($role->name === Role::$superAdmin){
            return redirect()->route('roles.index');
        }
        $validated = $request->validated();
        $role->update(['name' => $validated['name']]);
        foreach(Permission::$directPermissions as $permission){
            if(isset($validated[$permission])){
                $role->givePermissionTo($permission);
            } else {
                $role->revokePermissionTo($permission);
            }
        }
        return redirect()->route('roles.show', $role->id);
    }

    public function destroy(Role $role)
    {
        if($role->name !== Role::$superAdmin){
            $role->delete();
        }
        return redirect()->route('roles.index');
    }
}
