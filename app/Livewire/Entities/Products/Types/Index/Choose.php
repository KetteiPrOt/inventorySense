<?php

namespace App\Livewire\Entities\Products\Types\Index;

use App\Models\Products\Type;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Choose extends Component
{
    use WithPagination;

    public $search = null;

    #[Locked]
    public bool $showAllByDefault = true;

    public function mount(bool $showAllByDefault = true)
    {
        $this->showAllByDefault = $showAllByDefault;
    }

    public function render()
    {
        $types = $this->queryTypes();
        if($types?->count() == 0){
            $this->resetPage('product-type');
        }
        return view('livewire..entities.products.types.index.choose', [
            'types' => $types
        ]);
    }

    private function queryTypes(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $types = null;
        } else {
            $types =
                Type::whereRaw("`name` LIKE ?", ["%$validated%"])
                    ->orderBy('name')
                    ->simplePaginate(5, pageName: 'product-type');
        }
        return $types;
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
}
