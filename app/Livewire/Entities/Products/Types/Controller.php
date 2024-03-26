<?php

namespace App\Livewire\Entities\Products\Types;

use App\Models\Products\Type;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Controller extends Component
{
    use WithPagination;

    public $search = null;

    #[Layout('layouts.primary', ['header' => 'Tipos de producto'])]
    public function render()
    {
        $types = $this->queryTypes();
        if($types?->count() == 0){
            $this->resetPage();
        }
        return view('livewire..entities.products.types.controller', [
            'types' => $types
        ]);
    }

    private function queryTypes(): object|null
    {
        $validated = $this->validateSearch();
        return 
            Type::whereRaw("`name` LIKE ?", ["%$validated%"])
                ->orderBy('id')
                ->simplePaginate(15);
    }

    private function validateSearch(): string|null
    {
        $validated = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 49
            ){
                $validated = mb_strtoupper($this->search);
            }
        }
        return $validated;
    }

    public function delete(Type $type)
    {
        $type->delete();
    }
}
