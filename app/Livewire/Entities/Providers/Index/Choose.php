<?php

namespace App\Livewire\Entities\Providers\Index;

use App\Models\Provider;
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
            $providerSelectedByDefault = Provider::find($this->selectedByDefault);
        } else {
            $providers = $this->queryProviders();
            if($providers?->count() == 0){
                $this->resetPage('provider');
            }
        }
        return view('livewire..entities.providers.index.choose', [
            'providers' => $providers ?? null,
            'providerSelectedByDefault' => $providerSelectedByDefault ?? null,
            'required' => $this->required
        ]);
    }

    private function queryProviders(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $providers = null;
        } else {
            $providers =
                Provider::whereRaw("`name` LIKE ?", ["%$validated%"])
                    ->orderBy('name')
                    ->simplePaginate(5, pageName: 'provider');
        }
        return $providers;
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
