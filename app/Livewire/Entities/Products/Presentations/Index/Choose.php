<?php

namespace App\Livewire\Entities\Products\Presentations\Index;

use App\Models\Products\Presentation;
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
        $presentations = $this->queryPresentations();
        if($presentations?->count() == 0){
            $this->resetPage('product-presentation');
        }
        return view('livewire..entities.products.presentations.index.choose', [
            'presentations' => $presentations
        ]);
    }

    private function queryPresentations(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $presentations = null;
        } else {
            $presentations =
                Presentation::whereRaw("
                        CONCAT(`content`, 'ml') LIKE ?
                    ", ["%$validated%"])
                    ->orderBy('content')
                    ->simplePaginate(5, pageName: 'product-presentation');
        }
        return $presentations;
    }

    private function validateSearch(): string|null
    {
        $validated = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 49
            ){
                $validated = $this->search;
            }
        }
        return $validated;
    }
}
