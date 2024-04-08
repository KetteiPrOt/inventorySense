<?php

namespace App\Livewire\Entities\Invoices\Sales\Create;

use App\Models\Invoices\Movements\Type;
use App\Models\Products\Product;
use Illuminate\Support\Arr;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class MovementsInput extends Component
{
    use WithPagination;

    #[Locked]
    public array $selectedProducts = [];

    public $search;

    public function render()
    {
        $selectedProducts = $this->querySelectedProducts();
        $searchedProducts = $this->querySearchedProducts();
        if($searchedProducts->isEmpty()){
            $this->resetPage('products-searched');
        }
        return view('livewire.entities.invoices.sales.create.movements-input', [
            'selectedProductsCollection' => $selectedProducts,
            'searchedProducts' => $searchedProducts,
            'movementTypes' => Type::where('category', 'i')->get()
        ]);
    }

    private function querySelectedProducts(): object
    {
        // I use multiple queries because this component needs to keep the order of the array in the resulting Collection
        $products = [];
        foreach($this->selectedProducts as $productId){
            $products[] = Product::with('salePrices')->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
                ->selectRaw("
                    products.id,
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) as `tag`,
                    products.started_inventory
                ")->where('products.id', $productId)
                ->first();
        }
        return collect($products);
    }

    private function querySearchedProducts(): object
    {
        $search = $this->validateSearch();
        $products = collect([]);
        if($search){
            $products = 
                Product::with('latestBalance')->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                    ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
                    ->selectRaw("
                        products.id,
                        CONCAT_WS(' ',
                            `product_types`.`name`,
                            `products`.`name`,
                            CONCAT(`product_presentations`.`content`, 'ml')
                        ) as `tag`,
                        products.started_inventory
                    ")->whereRaw("
                        CONCAT_WS(' ',
                            `product_types`.`name`,
                            `products`.`name`,
                            CONCAT(`product_presentations`.`content`, 'ml')
                        ) LIKE ?
                    ", ["%$search%"])
                    ->whereNotIn('products.id', $this->selectedProducts)
                    ->orderBy('tag')
                    ->simplePaginate(5, pageName: 'products-searched');
        }
        return $products;
    }

    private function validateSearch(): string|null
    {
        $search = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) >= 2
                && mb_strlen($this->search) < 255
            ){
                $search = mb_strtoupper($this->search);
            }
        }
        return $search;
    }

    public function addProduct($id)
    {
        $id = $this->validateId($id);
        $product = Product::with('latestBalance')->find($id);
        if(
            !is_null($product)
            && $product->started_inventory
            && $product->latestBalance?->amount > 0
        ){
            $selectedProductsFlipped = array_flip($this->selectedProducts);
            if(!Arr::has($selectedProductsFlipped, $id)){
                $this->selectedProducts[] = $id;
            }
        }
    }

    private function validateId($id): int|null
    {
        $validId = null;
        if(is_numeric($id)){
            $id = intval($id);
            $validId = Product::where('id', $id)->value('id');
        }
        return $validId;
    }

    public function removeProduct($id)
    {
        $id = $this->validateId($id);
        if(!is_null($id)){
            $selectedProductsFlipped = array_flip($this->selectedProducts);
            if(Arr::has($selectedProductsFlipped, $id)){
                Arr::pull($selectedProductsFlipped, $id);
                $this->selectedProducts = array_flip($selectedProductsFlipped);
            }
        }
    }
}
