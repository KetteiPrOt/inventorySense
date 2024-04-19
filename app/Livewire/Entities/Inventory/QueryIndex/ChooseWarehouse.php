<?php

namespace App\Livewire\Entities\Inventory\QueryIndex;

use App\Livewire\Entities\Warehouses\Index\Choose;
use App\Models\Warehouse;

class ChooseWarehouse extends Choose
{
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
        return view('livewire..entities.inventory.query-index.choose-warehouse', [
            'warehouses' => $warehouses ?? null,
            'warehouseSelectedByDefault' => $warehouseSelectedByDefault ?? null,
            'required' => $this->required
        ]);
    }
}
