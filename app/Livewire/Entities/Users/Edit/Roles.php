<?php

namespace App\Livewire\Entities\Users\Edit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Roles extends Component
{
    use WithPagination;

    #[Locked]
    public ?User $user = null;

    public $search;

    public function mount(?User $user)
    {
        $this->user = $user->load('roles');
    }

    public function render()
    {
        $roles = $this->queryRoles();
        foreach($roles as $key => $role){
            $role->n =
                ($key + 1) + ($roles->currentPage() - 1) * $roles->perPage();
        }
        if($roles->isEmpty()){
            $this->resetPage('roles');
        }
        return view('livewire..entities.users.edit.roles', [
            'user' => $this->user,
            'roles' => $roles,
            'superAdmin' => Role::$superAdmin
        ]);
    }

    private function queryRoles(): object
    {
        $validated = $this->validateSearch();
        $unabledRoles = $this->user->roles->pluck('id')->toArray();
        if(!Arr::has($unabledRoles, Role::superAdmin()->id)){
            $unabledRoles[] = Role::superAdmin()->id;
        }
        $roles = 
            Role::whereRaw("`name` LIKE ?", ["%$validated%"])
                ->whereNotIn('id', $unabledRoles)
                ->orderBy('name')
                ->simplePaginate(5, pageName: 'roles');
        return $roles;
    }

    public function assignRole(Role $role)
    {
        if($role->name !== Role::$superAdmin){
            $this->user->assignRole($role->name);
        }
    }

    public function removeRole(Role $role)
    {
        if($role->name !== Role::$superAdmin){
            $this->user->removeRole($role->name);
        }
    }

    private function validateSearch(): string|null
    {
        $validated = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 255
            ){
                $validated = mb_strtoupper($this->search);
            }
        }
        return $validated;
    }
}
