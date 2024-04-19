<?php

namespace App\Livewire\Entities\Warehouses\Index;

use App\Models\Warehouse;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Choose extends Component
{
    use WithPagination;

    protected ?int $selectedByDefault = null;

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
            $warehouseSelectedByDefault = Warehouse::find($this->selectedByDefault);
        } else {
            $warehouses = $this->queryWarehouses();
            if($warehouses?->count() == 0){
                $this->resetPage('warehouse');
            }
        }
        return view('livewire..entities.warehouses.index.choose', [
            'warehouses' => $warehouses ?? null,
            'warehouseSelectedByDefault' => $warehouseSelectedByDefault ?? null,
            'required' => $this->required
        ]);
    }

    protected function queryWarehouses(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $warehouses = null;
        } else {
            $warehouses =
                Warehouse::whereRaw("`name` LIKE ?", ["%$validated%"])
                    ->orderBy('name')
                    ->simplePaginate(5, pageName: 'warehouse');
        }
        return $warehouses;
    }

    protected function validateSearch(): string|null
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
