<?php

namespace App\Livewire\Entities\Products\Index;

use App\Models\Products\Product;
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

    #[Locked]
    public bool $onlyWithStartedInventory = true;

    public function mount(
        bool $showAllByDefault = true,
        int $selectedByDefault = null,
        bool $required = true,
        bool $onlyWithStartedInventory = true
    )
    {
        $this->showAllByDefault = $showAllByDefault;
        $this->selectedByDefault = $selectedByDefault;
        $this->required = $required;
        $this->onlyWithStartedInventory = $onlyWithStartedInventory;
    }

    public function render()
    {
        if($this->selectedByDefault){
            $productSelectedByDefault = Product::find($this->selectedByDefault);
            $productSelectedByDefault?->loadTag();
        } else {
            $products = $this->queryProducts();
            if($products?->count() == 0){
                $this->resetPage('products');
            }
        }
        return view('livewire.entities.products.index.choose', [
            'products' => $products ?? null,
            'productSelectedByDefault' => $productSelectedByDefault ?? null,
            'required' => $this->required,
            'onlyWithStartedInventory' => $this->onlyWithStartedInventory
        ]);
    }

    private function queryProducts(): object|null
    {
        $validated = $this->validateSearch();
        if(!$this->showAllByDefault && is_null($validated)){
            $products = null;
        } else {
            $search = mb_strtoupper($validated);
            $products =
                Product::leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                    ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
                    ->selectRaw("
                        products.id,
                        CONCAT_WS(' ',
                            `product_types`.`name`,
                            `products`.`name`,
                            CONCAT(`product_presentations`.`content`, 'ml')
                        ) as `tag`,
                        products.started_inventory
                    ")
                    ->whereRaw("
                        CONCAT_WS(' ',
                            `product_types`.`name`,
                            `products`.`name`,
                            CONCAT(`product_presentations`.`content`, 'ml')
                        ) LIKE ?
                    ", ["%$search%"])
                    ->orderBy('tag')
                    ->simplePaginate(5, pageName: 'products')->withQueryString();
        }
        return $products;
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
