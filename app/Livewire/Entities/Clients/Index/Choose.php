<?php

namespace App\Livewire\Entities\Clients\Index;

use App\Models\Client;
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
            $clientSelectedByDefault = Client::find($this->selectedByDefault);
        } else {
            $clients = $this->queryClients();
            if($clients?->count() == 0){
                $this->resetPage('client');
            }
        }
        return view('livewire..entities.clients.index.choose', [
            'clients' => $clients ?? null,
            'clientSelectedByDefault' => $clientSelectedByDefault ?? null,
            'required' => $this->required
        ]);
    }

    private function queryClients(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $clients = null;
        } else {
            $clients =
                Client::whereRaw("`name` LIKE ?", ["%$validated%"])
                    ->orderBy('name')
                    ->simplePaginate(5, pageName: 'client');
        }
        return $clients;
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
