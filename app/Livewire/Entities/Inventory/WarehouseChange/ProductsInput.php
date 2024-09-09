<?php

namespace App\Livewire\Entities\Inventory\WarehouseChange;

use App\Models\Products\Product;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsInput extends Component
{
    use WithPagination;

    #[Validate('nullable|string|max:255', 'buscar', onUpdate: false)]
    public $search;

    #[Locked]
    public $selectedProductsIds = [];

    private $products;

    #[Locked]
    public $from_warehouse;

    public function mount(int $fromWarehouse)
    {
        $this->from_warehouse = $fromWarehouse;
    }

    public function render()
    {
        $this->checkProducts();
        return view('livewire..entities.inventory.warehouse-change.products-input', [
            'products' => $this->products,
            'selectedProducts' => $this->querySelectedProducts()
        ]);
    }

    public function updated($name, $value)
    {
        $validated = $this->validate();
        $this->products = $this->queryProducts($validated['search'] ?? null);
        $this->resetPage();
    }

    private function checkProducts()
    {
        if(is_null($this->products)){
            $this->products = $this->queryProducts();
        }
    }

    private function queryProducts(?string $search = null)
    {
        $products = Product::joinTag($search)
                           ->whereNotIn('products.id', $this->selectedProductsIds)
                           ->orderBy('tag')
                           ->paginate(5);
        foreach($products as $product){
            $product->loadWarehouseExistences($this->from_warehouse);
        }
        return $products;
    }

    private function querySelectedProducts()
    {
        $products = collect([]);
        foreach($this->selectedProductsIds as $id){
            $product = Product::joinTag()
                              ->where('products.id', $id)
                              ->first();
            $product->loadWarehouseExistences($this->from_warehouse);
            $products->push($product);
        }
        return $products;
    }

    public function selectProduct($id)
    {
        $id = $this->validatedId($id);
        if(isset($id)){
            $this->selectedProductsIds[] = $id;
        }
    }

    public function removeProduct($id)
    {
        $id = $this->validatedId($id);
        $key = array_search($id, $this->selectedProductsIds);
        if(is_int($key)){
            unset($this->selectedProductsIds[$key]);
        }
    }

    private function validatedId(mixed $id)
    {
        if(is_int($id)){
            $id = Product::find($id)?->id;
        } else {
            $id = null;
        }
        return $id;
    }
}
