<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255',
            'column' => 'nullable|string|size:3',
            'order' => 'nullable|string|min:3|max:4'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('products.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $query = 
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
                ");
        if(isset($validated['search'])){
            $search = mb_strtoupper($validated['search']);
            $query->whereRaw("
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) LIKE ?
                ", ["%$search%"]);
        }
        $column = match($validated['column'] ?? null){
            'tag' => 'tag', default => 'tag'
        };
        $order = match($validated['order'] ?? null){
            'desc' => 'desc', 'asc' => 'asc', default => 'asc'
        };
        $products = $query
            ->orderBy($column, $order)
            ->paginate(15)->withQueryString();
        foreach($products as $key => $product){
            $product->n =
                ($key + 1) + ($products->currentPage() - 1) * $products->perPage();
        }
        return view('entities.products.index', [
            'products' => $products,
            'filters' => [
                'column' => $column,
                'order' => $order
            ]
        ]);
    }

    public function create()
    {
        return view('entities.products.create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $product = Product::create([
            'name' => $validated['product_name'],
            'min_stock' => $validated['min_stock'],
            'presentation_id' => $validated['product_presentation'],
            'type_id' => $validated['product_type']
        ]);
        foreach($validated['sale_prices'] as $key => $salePrice){
            SalePrice::create([
                'price' => $salePrice,
                'units_number' => $validated['units_numbers'][$key],
                'product_id' => $product->id
            ]);
        }
        return redirect()->route('products.show', $product->id);
    }

    public function show(Product $product)
    {
        $product->load(['presentation', 'type', 'salePrices']);
        $product->salePrices = $product->salePrices->sortBy('units_number');
        $product->loadTag();
        return view('entities.products.show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product)
    {
        $product->load(['presentation', 'type', 'salePrices']);
        $product->salePrices = $product->salePrices->sortBy('units_number');
        return view('entities.products.edit', [
            'product' => $product
        ]);
    }

    public function update(UpdateRequest $request, Product $product)
    {
        $validated = $request->validated();
        $product->update([
            'name' => $validated['product_name'],
            'min_stock' => $validated['min_stock'],
            'presentation_id' => $validated['product_presentation'],
            'type_id' => $validated['product_type']
        ]);
        $product->salePrices->each(fn($salePrice) => $salePrice->delete());
        foreach($validated['sale_prices'] as $key => $newSalePrice){
            SalePrice::create([
                'price' => $newSalePrice,
                'units_number' => $validated['units_numbers'][$key],
                'product_id' => $product->id
            ]);
        }
        $product->setUpdatedAt(date('Y-m-d H:i:s'));
        $product->save();
        return redirect()->route('products.show', $product->id);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}
