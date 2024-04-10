<?php

namespace App\Livewire\Entities\Users\Index;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Choose extends Component
{
    use WithPagination;

    private ?int $selectedByDefault = null;

    #[Locked]
    public bool $required = true;

    public $search = null;

    #[Locked]
    public bool $showAllByDefault = true;

    public function mount(
        bool $showAllByDefault = true,
        int $selectedByDefault = null,
        bool $required = true
    )
    {
        $this->showAllByDefault = $showAllByDefault;
        $this->selectedByDefault = $selectedByDefault;
        $this->required = $required;
    }

    public function render()
    {
        if($this->selectedByDefault){
            $userSelectedByDefault = User::find($this->selectedByDefault);
        } else {
            $users = $this->queryUsers();
            if($users?->count() == 0){
                $this->resetPage('users');
            }
        }
        return view('livewire..entities.users.index.choose', [
            'users' => $users ?? null,
            'userSelectedByDefault' => $userSelectedByDefault ?? null,
            'required' => $this->required
        ]);
    }

    private function queryUsers(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $users = null;
        } else {
            $users =
                User::whereRaw("`name` LIKE ?", ["%$validated%"])
                    ->orderBy('name')
                    ->simplePaginate(5, pageName: 'users');
        }
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
}
