<?php

namespace App\Livewire\Entities\Roles\Edit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    #[Locked]
    public ?Role $role = null;

    public $search;

    public function mount(?Role $role)
    {
        $this->role = $role->load('users');
    }

    public function render()
    {
        $users = $this->queryUsers();
        foreach($users as $key => $user){
            $user->n =
                ($key + 1) + ($users->currentPage() - 1) * $users->perPage();
        }
        if($users->isEmpty()){
            $this->resetPage('users');
        }
        return view('livewire..entities.roles.edit.users', [
            'role' => $this->role,
            'users' => $users,
            'superAdmin' => Role::$superAdmin
        ]);
    }

    private function queryUsers(): object
    {
        $validated = $this->validateSearch();
        $unabledUsers = $this->role->users->pluck('id')->toArray();
        $users = 
            User::whereRaw("`name` LIKE ?", ["%$validated%"])
                ->whereNotIn('id', $unabledUsers)
                ->orderBy('name')
                ->simplePaginate(5, pageName: 'users');
        return $users;
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

    public function assignUser(User $user)
    {
        $user->assignRole($this->role->name);
    }

    public function removeUser(User $user)
    {
        $user->removeRole($this->role->name);
    }
}
